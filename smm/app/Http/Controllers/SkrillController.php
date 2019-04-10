<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use Validator;
use App\PaymentLog;
use App\Transaction;
use App\PaymentMethod;
use Illuminate\Http\Request;

class SkrillController extends Controller
{
    private $payment_method_id = 7;
    private $skrill_email;
    private $skrill_secret;
    const SKRILL_URL = "https://pay.skrill.com/?";

    public function __construct()
    {
        $this->skrill_email = PaymentMethod::where(['config_key' => 'skrill_email'])->first()->config_value;
        $this->skrill_secret = PaymentMethod::where(['config_key' => 'skrill_secret'])->first()->config_value;
    }

    public function show(Request $request)
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

        return view('payments.skrill');
    }

    public function store(Request $request)
    {
        $minimum_deposit_amount = getOption('minimum_deposit_amount');
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minimum_deposit_amount
        ]);

        if ($validator->fails()) {
            return redirect('payment/add-funds/skrill')
                ->withErrors($validator)
                ->withInput();
        }

        $paymentLogSecret = bcrypt(Auth::user()->email . 'Skrill' . time() . rand(1, 90000));

        // Create payment logs
        PaymentLog::create([
            'currency_code' => strtoupper(getOption('currency_code')),
            'details' => $paymentLogSecret,
            'total_amount' => $request->input('amount'),
            'payment_method_id' => $this->payment_method_id,
            'user_id' => Auth::user()->id
        ]);

        $params = [
            'pay_to_email' => $this->skrill_email,
            'language' => getOption('language'),
            'amount' => $request->input('amount'),
            'detail1_description' => 'Add funds user:' . Auth::user()->email,
            'currency' => strtoupper(getOption('currency_code')),
            'return_url' => url('/payment/add-funds/skrill/success'),
            'cancel_url' => url('/payment/add-funds/skrill/cancel'),
            'status_url' => url('/payment/add-funds/skrill/ipn'),
            'custom' => $paymentLogSecret,
            'logo_url' => asset(getOption('logo')),
            'transaction_id' => $paymentLogSecret
        ];

        $url = self::SKRILL_URL . http_build_query($params);
        return redirect()->away($url);
    }

    public function success(Request $request)
    {
        Session::flash('alert', __('messages.payment_success'));
        Session::flash('alertClass', 'success');
        return redirect('/payment/add-funds/skrill');
    }

    public function cancel(Request $request)
    {
        Session::flash('alert', __('messages.payment_failed'));
        Session::flash('alertClass', 'danger no-auto-close');
        return redirect('/payment/add-funds/skrill');
    }

    public function ipn(Request $request)
    {
        if (!$request->has('merchant_id') || !$request->has('md5sig')) {
            activity('skrill')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Missing $_POST data');
            die();
        }

        $merchant_id = $request->input('merchant_id');
        $secret_word = strtoupper(md5($this->skrill_secret));
        $transaction_id = $request->input('transaction_id');
        $mb_amount = $request->input('mb_amount');
        $mb_currency = $request->input('mb_currency');
        $status = $request->input('status');
        $string = $merchant_id . $transaction_id . $secret_word . $mb_amount . $mb_currency . $status;

        if (strtoupper(md5($string)) != $request->input('md5sig')) {
            activity('skrill')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Skrill md5Sig mismatch');
            die();
        }

        if ($request->input('pay_to_email') != $this->skrill_email) {
            activity('skrill')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Email in Panel is not same as received from skrill merchant email. received mail:' . $request->input('pay_to_email'));
            die();
        }

        $paymentLog = PaymentLog::where(['details' => $request->input('transaction_id')])->first();

        if ($request->input('amount') != $paymentLog->total_amount) {
            activity('skrill')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Total amount is different sent:' . $paymentLog->total_amount . ' received:' . $request->input('amount'));
            die();
        }


        if ($request->input('status') == 2) {

            Transaction::create([
                'amount' => $request->input('mb_amount'),
                'payment_method_id' => $this->payment_method_id,
                'user_id' => $paymentLog->user_id,
                'details' => "mb_transaction_id: " . $request->input('mb_transaction_id')
            ]);

            $user = User::find($paymentLog->user_id);
            $user->funds = $user->funds + $request->input('mb_amount');
            $user->save();

            // Payment successful, load fund and update payment log
            PaymentLog::where(['details' => $request->input('transaction_id')])->update([
                'details' => json_encode($request->all()),
            ]);

            activity('skrill')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('Payment Successful for user_id:' . $paymentLog->user_id . ' amount:' . $request->input('mb_amount'));
            die();
        }


        //if not above condition matched than
        activity('skrill')
            ->withProperties([
                'ip' => $request->ip()
            ])->log('Payment not successful');
        die();

    }

}
