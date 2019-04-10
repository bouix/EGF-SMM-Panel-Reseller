<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers;

use App\PaymentLog;
use App\PaymentMethod;
use App\Transaction;
use App\User;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoinPaymentsController extends Controller
{
    private $url = 'https://www.coinpayments.net/index.php?';
    private $merchantId = '';
    private $secretKey = '';
    private $payment_method_id = 3; // Bitcoin payment id in table `payment_methods`

    public function __construct()
    {
        $this->merchantId = PaymentMethod::where(['config_key' => 'merchant_id'])->first()->config_value;
        $this->secretKey = PaymentMethod::where(['config_key' => 'secret_key'])->first()->config_value;
    }

    public function showForm()
    {
        // check if payment method is not enabled then abort
        $paymentMethod = PaymentMethod::where(['id' => $this->payment_method_id, 'status' => 'ACTIVE'])->first();
        if (is_null($paymentMethod)) {
            abort(403);
        }

        // User have assigned payment methods?
        if (empty(Auth::user()->enabled_payment_methods)) {
            abort(403);
        }
        // Get users enabled payment methods & see if this method is enabled for him.
        $enabled_payment_methods = explode(',', Auth::user()->enabled_payment_methods);
        if (!in_array($this->payment_method_id, $enabled_payment_methods)) {
            abort(403);
        }

        return view('payments.bitcoin');
    }

    public function store(Request $request)
    {
        //minimum deposit validation
        $minimum_deposit_amount = getOption('minimum_deposit_amount');
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minimum_deposit_amount
        ]);

        if ($validator->fails()) {
            return redirect('payment/add-funds/bitcoin')
                ->withErrors($validator)
                ->withInput();
        }

        $params = [
            'merchant' => $this->merchantId,
            'cmd' => '_pay_simple',
            'reset' => 1,
            'currency' => getOption('currency_code'),
            'amountf' => $request->input('amount'),
            'item_name' => 'Add Funds',
            'email' => Auth::user()->email,
            'ipn_url' => url('/payment/add-funds/bitcoin/bit-ipn'),
            'success_url' => url('/payment/add-funds/bitcoin/success'),
            'cancel_url' => url('/payment/add-funds/bitcoin/cancel'),
            'first_name' => Auth::user()->name,
            'last_name' => Auth::user()->name,
            'want_shipping' => 0,
        ];

        $paymentLogSecret = bcrypt(Auth::user()->email . 'PayPal' . time() . rand(1, 90000));
        // Create payment logs
        PaymentLog::create([
            'currency_code' => strtoupper(getOption('currency_code')),
            'details' => $paymentLogSecret,
            'total_amount' => $request->input('amount'),
            'payment_method_id' => $this->payment_method_id,
            'user_id' => Auth::user()->id
        ]);

        $params['custom'] = $paymentLogSecret;
        $this->url .= http_build_query($params);
        return redirect()->away($this->url);
    }

    public function success(Request $request)
    {
        Session::flash('alert', __('messages.payment_success'));
        Session::flash('alertClass', 'success');
        return redirect('/payment/add-funds/bitcoin');
    }

    public function cancel(Request $request)
    {
        Session::flash('alert', __('messages.payment_failed'));
        Session::flash('alertClass', 'danger no-auto-close');
        return redirect('/payment/add-funds/bitcoin');
    }

    public function ipn(Request $request)
    {
        if (!$request->filled('ipn_mode') || !$request->filled('merchant')) {
            activity('coinpayments')
                ->withProperties(['ip' => $request->ip()])
                ->log('Missing POST data from callback.');
            die();
        }

        if ($request->input('ipn_mode') == 'httpauth') {
            //Verify that the http authentication checks out with the users supplied information
            if ($request->server('PHP_AUTH_USER') != $this->merchantId || $request->server('PHP_AUTH_PW') != $this->secretKey) {
                activity('coinpayments')
                    ->withProperties(['ip' => $request->ip()])
                    ->log('Unauthorized HTTP Request');
                die();
            }

        } elseif ($request->input('ipn_mode') == 'hmac') {
            // Create the HMAC hash to compare to the recieved one, using the secret key.
            $hmac = hash_hmac("sha512", $request->all(), $this->secretKey);

            if ($hmac != $request->server('HTTP_HMAC')) {
                activity('coinpayments')
                    ->withProperties(['ip' => $request->ip()])
                    ->log('Unauthorized HMAC Request');
                die();
            }

        } else {
            activity('coinpayments')
                ->withProperties(['ip' => $request->ip()])
                ->log('Unauthorized HMAC Request');
            die();
        }

        // Passed initial security test - now check the status
        $status = intval($request->input('status'));
        $statusText = $request->input('status_text');

        if ($request->input('merchant') != $this->merchantId) {
            activity('coinpayments')
                ->withProperties(['ip' => $request->ip()])
                ->log('Mismatching merchant ID. MerchantID:' . $request->input('merchant'));
            die();
        }

        if ($status < 0) {
            activity('coinpayments')
                ->withProperties([
                    'ip' => $request->ip(),
                    'status' => $status,
                    'StatusText' => $statusText
                ])
                ->log('Payment Failed');
            die();

        } elseif ($status == 0) {
            activity('coinpayments')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Payment is in Pending, Waiting for buyer funds');
            die();
        } elseif ($status >= 100 || $status == 2) {

            if (!$request->filled('custom')) {
                activity('coinpayments')
                    ->withProperties([
                        'ip' => $request->ip()
                    ])->log('custom data is missing from request');
                die();
            }

            $custom = $request->input('custom');

            $paymentLog = PaymentLog::where(['details' => $custom])->first();
            if (!is_null($paymentLog)) {
                $txn_id = $request->input('txn_id');
                $item_name = $request->input('item_name');
                $amount1 = $request->input('amount1');
                $amount2 = $request->input('amount2');
                $fee = $request->input('fee');
                $tax = $request->input('tax');
                $currency1 = $request->input('currency1');
                $currency2 = $request->input('currency2');

                // Check the original currency to make sure the buyer didn't change it.
                if (strtolower($currency1) != strtolower(getOption('currency_code'))) {
                    activity('coinpayments')
                        ->withProperties([
                            'ip' => $request->ip()
                        ])->log('Original currency mismatch. Currency:' . $currency1);
                    die();
                }

                // Check amount against order total
                if ($amount1 < $paymentLog->total_amount) {
                    activity('coinpayments')
                        ->withProperties([
                            'ip' => $request->ip()
                        ])->log('Amount is less than order total. Amount:' . $amount1);
                    die();
                }

                // Payment successful, load fund and update payment log
                PaymentLog::where(['details' => $custom])->update([
                    'details' => json_encode($request->all()),
                ]);

                $amountAfterTax = $amount1 - $tax;

                // Create Transaction logs
                Transaction::create([
                    'amount' => $amountAfterTax,
                    'payment_method_id' => $this->payment_method_id,
                    'user_id' => $paymentLog->user_id
                ]);

                $user = User::find($paymentLog->user_id);
                $user->funds = $user->funds + $amountAfterTax;
                $user->save();

                activity('coinpayments')
                    ->withProperties([
                        'ip' => $request->ip()
                    ])->log('Payment Loaded successfully for user_id:' . $paymentLog->user_id . ' amount:' . $amountAfterTax);
                die();

            }

            activity('coinpayments')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('PaymentLog Object not found, might be payment already loaded.');
            die();

        }

        activity('coinpayments')
            ->withProperties([
                'ip' => $request->ip()
            ])->log('Unkown error, no condition matched.');
        die();
    }
}
