<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use Auth;
use Session;
use Validator;
use App\PaymentLog;
use App\PaymentMethod;
use Illuminate\Http\Request;

class PayzaController extends Controller
{


    const PAYMENT_METHOD_ID = 4; // Payza payment id in table `payment_methods`
    const API_URL = 'https://secure.payza.com/checkout?'; // pazya end point url
    const IPN_V2_HANDLER = 'https://secure.payza.com/ipn2.ashx';
    private $merchantId = '';
    private $mode = '';

    public function __construct()
    {
        $this->merchantId = PaymentMethod::where(['config_key' => 'ap_merchant'])->first()->config_value;
        $this->mode = PaymentMethod::where(['config_key' => 'payza_mode'])->first()->config_value;

    }

    public function show()
    {
        // check if payment method is not enabled then abort
        $paymentMethod = PaymentMethod::where(['id' => self::PAYMENT_METHOD_ID, 'status' => 'ACTIVE'])->first();
        if (is_null($paymentMethod)) {
            abort(403);
        }

        // User have assigned payment methods?
        if (empty(Auth::user()->enabled_payment_methods)) {
            abort(403);
        }
        // Get users enabled payment methods & see if this method is enabled for him.
        $enabled_payment_methods = explode(',', Auth::user()->enabled_payment_methods);
        if (!in_array(self::PAYMENT_METHOD_ID, $enabled_payment_methods)) {
            abort(403);
        }

        return view('payments.payza');
    }

    public function store(Request $request)
    {
        //minimum deposit validation
        $minimum_deposit_amount = getOption('minimum_deposit_amount');
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minimum_deposit_amount
        ]);

        if ($validator->fails()) {
            return redirect('payment/add-funds/payza')
                ->withErrors($validator)
                ->withInput();
        }

        // Store payment log attempt into the database first
        // Also useful to get verify secrets
        $paymentLogSecret = bcrypt(Auth::user()->email . time() . rand(1, 99999));
        // Create payment logs
        PaymentLog::create([
            'currency_code' => strtoupper(getOption('currency_code')),
            'details' => $paymentLogSecret,
            'total_amount' => $request->input('amount'),
            'payment_method_id' => self::PAYMENT_METHOD_ID,
            'user_id' => Auth::user()->id
        ]);

        $params = [
            'ap_merchant' => $this->merchantId,
            'ap_purchasetype' => 'item',
            'ap_currency' => getOption('currency_code'),
            'ap_amount' => $request->input('amount'),
            'ap_itemname' => 'Add Funds',
            'ap_alerturl' => url('/payment/add-funds/payza/status'),
            'ap_returnurl' => url('/payment/add-funds/payza/success'),
            'ap_cancelurl' => url('/payment/add-funds/payza/cancel'),
            'ap_description' => 'Load funds to panel.',
            'ap_quantity' => '1',
            'ap_ipnversion' => '2',
            'ap_testmode' => ($this->mode == 'live') ? 0 : 1,
            'apc_1' => $paymentLogSecret,
        ];

        $url = self::API_URL . http_build_query($params);
        return redirect()->away($url);
    }

    public function ipn(Request $request)
    {
        $token = urlencode($request->input('token'));
        $response = '';

        // send the URL encoded TOKEN string to the Payza's IPN handler
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::IPN_V2_HANDLER);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // $response holds the response string from the Payza's IPN.
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response != false) {
            if (urldecode($response) == "INVALID TOKEN") {
                activity('payza')
                    ->withProperties(['ip' => $request->ip()])
                    ->log('Invalid token');
                die();
            } else {

                $response = urldecode($response);
                // Extract Data
                parse_str($response, $data);

                // Get payment log stored while redirecting to payza checkout.
                $apc_1 = $data['apc_1'];
                $paymentLog = PaymentLog::where(['details' => $apc_1])->first();

                // If payment log is found then it means
                // the request is same which was sent
                if (!is_null($paymentLog)) {
                    $ap_merchant = $data['ap_merchant'];
                    $ap_status = $data['ap_status'];
                    $ap_referencenumber = $data['ap_referencenumber']; // Transaction id in Payza
                    $ap_itemname = $data['ap_itemname'];
                    $ap_currency = $data['ap_currency'];
                    $ap_totalamount = $data['ap_totalamount'];
                    $ap_notificationtype = $data['ap_notificationtype'];
                    $ap_transactionstate = $data['ap_transactionstate'];
                    $ap_netamount = $data['ap_netamount'];


                    // Check if the merchant_id is not different
                    if ($ap_merchant != $this->merchantId) {
                        activity('payza')
                            ->withProperties([
                                'ip' => $request->ip()
                            ])->log('merchant received from payza is different then added in system. merchant: ' . $ap_merchant);
                        die();
                    }

                    // Check the original currency to make sure the buyer didn't change it.
                    if (strtolower($ap_currency) != strtolower(getOption('currency_code'))) {
                        activity('payza')
                            ->withProperties([
                                'ip' => $request->ip()
                            ])->log('Original currency mismatch!. currency: ' . $ap_currency);
                        die();
                    }

                    // Check amount against order total
                    if ($ap_totalamount < $paymentLog->total_amount) {
                        activity('payza')
                            ->withProperties([
                                'ip' => $request->ip()
                            ])->log('Amount is less than order total!. amount:' . $ap_totalamount);
                        die();
                    }

                    // Notification type is 'New' And
                    // Transaction state is 'Completed'
                    // And ap_status is 'Success'
                    if (strtolower($ap_transactionstate) == 'completed'
                        && strtolower($ap_notificationtype) == 'new'
                        && strtolower($ap_status) == 'success') {

                        // Payment successful and update payment log
                        PaymentLog::where(['details' => $apc_1])->update([
                            'details' => json_encode($data),
                        ]);

                        // Create Transaction logs
                        Transaction::create([
                            'amount' => $ap_netamount,
                            'payment_method_id' => self::PAYMENT_METHOD_ID,
                            'user_id' => $paymentLog->user_id
                        ]);

                        $user = User::find($paymentLog->user_id);
                        $user->funds = $user->funds + $ap_netamount;
                        $user->save();

                        activity('payza')
                            ->withProperties([
                                'ip' => $request->ip()
                            ])->log('Payment Loaded successfully for user_id:' . $paymentLog->user_id . ' amount:' . $ap_netamount);
                        die();
                    }

                }

                activity('payza')
                    ->withProperties([
                        'ip' => $request->ip()
                    ])->log('PaymentLog Object not found, details: ' . $apc_1);
                die();
            }
        } else {
            activity('payza')
                ->withProperties(['ip' => $request->ip])
                ->log('Token received and token sent mismatched from payza server.');
            die();
        }
    }

    public function cancel()
    {
        Session::flash('alert', __('messages.payment_failed'));
        Session::flash('alertClass', 'danger no-auto-close');
        return redirect('/payment/add-funds/payza');
    }

    public function success()
    {
        Session::flash('alert', __('messages.payment_success'));
        Session::flash('alertClass', 'success');
        return redirect('/payment/add-funds/payza');
    }
}
