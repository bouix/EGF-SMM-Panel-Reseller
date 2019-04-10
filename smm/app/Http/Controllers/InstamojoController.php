<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use Validator;
use App\PaymentLog;
use App\Transaction;
use GuzzleHttp\Client;
use App\PaymentMethod;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;

class InstamojoController extends Controller
{
    private $payment_method_id = 6;
    private $instamojo_token;
    private $instamojo_api_key;
    private $instamojo_salt;
    private $instamojo_indian_rupees_valued_1_usd;
    #const INSTAMOJO_URL = "https://test.instamojo.com/api/1.1/payment-requests/";
    const INSTAMOJO_URL = "https://www.instamojo.com/api/1.1/payment-requests/";

    public function __construct()
    {
        $this->instamojo_api_key = PaymentMethod::where(['config_key' => 'instamojo_api_key'])->first()->config_value;
        $this->instamojo_token = PaymentMethod::where(['config_key' => 'instamojo_token'])->first()->config_value;
        $this->instamojo_salt = PaymentMethod::where(['config_key' => 'instamojo_salt'])->first()->config_value;
        $this->instamojo_indian_rupees_valued_1_usd = PaymentMethod::where(['config_key' => 'instamojo_indian_rupees_valued_1_usd'])->first()->config_value;
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

        return view('payments.instamojo');
    }

    public function store(Request $request)
    {
        $minimum_deposit_amount = getOption('minimum_deposit_amount');
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minimum_deposit_amount,
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('payment/add-funds/instamojo')
                ->withErrors($validator)
                ->withInput();
        }

        $amount = $request->input('amount');
        //Check if panel currency is USD
        if (strtoupper(getOption('currency_code')) == 'USD') {
            $amount = ($request->input('amount') * $this->instamojo_indian_rupees_valued_1_usd);
        }


        // create new client and make call
        $client = new Client();
        try {

            $res = $client->request("POST", self::INSTAMOJO_URL, [
                'form_params' => [
                    'purpose' => 'Add Funds',
                    'amount' => $amount,
                    'phone' => $request->input('phone'),
                    'buyer_name' => $request->input('name'),
                    'redirect_url' => url('/payment/add-funds/instamojo/return'),
                    'send_email' => false,
                    'webhook' => url('/payment/add-funds/instamojo/webhook'),
                    'send_sms' => false,
                    'email' => $request->input('email'),
                    'allow_repeated_payments' => false
                ],
                'headers' => [
                    'X-Api-Key' => $this->instamojo_api_key,
                    'X-Auth-Token' => $this->instamojo_token,
                ],
            ]);

            if ($res->getStatusCode() === 200 || $res->getStatusCode() === 201) {
                $resp = $res->getBody()->getContents();
                $r = json_decode($resp);

                // Create payment logs
                PaymentLog::create([
                    'currency_code' => strtoupper(getOption('currency_code')),
                    'details' => $r->payment_request->id,
                    'total_amount' => $request->input('amount'),
                    'payment_method_id' => $this->payment_method_id,
                    'user_id' => Auth::user()->id
                ]);

                return redirect()->away($r->payment_request->longurl);
            }

            Session::flash('alert', __('messages.payment_failed'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();

        } catch (ClientException $e) {
            if ($e->getCode() == 400) {
                Session::flash('alert', __('Error: Please check you have entered correct details.'));
                Session::flash('alertClass', 'danger no-auto-close');
                return redirect()->back()->withInput();
            }

            Session::flash('alert', __('messages.payment_failed'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();
        }
    }

    public function redirectReturn(Request $request)
    {
        Session::flash('alert', __('messages.payment_success'));
        Session::flash('alertClass', 'success');
        return redirect('/payment/add-funds/instamojo');
    }

    public function webhook(Request $request)
    {

        if (!$request->has('mac')) {
            activity('Instamojo')
                ->withProperties(['ip' => $request->ip()])
                ->log('No POST data');
            die();
        }

        $data = $_POST;
        $mac_provided = $data['mac'];  // Get the MAC from the POST data
        unset($data['mac']);  // Remove the MAC key from the data.
        $ver = explode('.', phpversion());
        $major = (int)$ver[0];
        $minor = (int)$ver[1];
        if ($major >= 5 and $minor >= 4) {
            ksort($data, SORT_STRING | SORT_FLAG_CASE);
        } else {
            uksort($data, 'strcasecmp');
        }

        $mac_calculated = hash_hmac("sha1", implode("|", $data), $this->instamojo_salt);
        if ($mac_provided == $mac_calculated) {
            if ($data['status'] == "Credit") {

                $paymentLog = PaymentLog::where(['details' => $data['payment_request_id']])->first();
                if (is_null($paymentLog)) {
                    activity('Instamojo')
                        ->withProperties(['ip' => $request->ip()])
                        ->log('Payment Log not found. details: ' . $data['payment_request_id']);
                    die();
                }

                // Amount after fees deduction
                $amount_after_fee = $data['amount'] - $data['fees'];

                //Check if panel currency is USD
                if (strtoupper(getOption('currency_code')) == 'USD') {
                    $amount_after_fee = $paymentLog->total_amount;
                }


                Transaction::create([
                    'amount' => $amount_after_fee,
                    'payment_method_id' => $this->payment_method_id,
                    'user_id' => $paymentLog->user_id,
                    'details' => "payment_id: " . $data['payment_id']
                ]);

                $user = User::find($paymentLog->user_id);
                $user->funds = $user->funds + $amount_after_fee;
                $user->save();

                // Payment successful, load fund and update payment log
                PaymentLog::where(['details' => $data['payment_request_id']])->update([
                    'details' => json_encode($data),
                ]);

                activity('Instamojo')
                    ->withProperties([
                        'ip' => $request->ip()
                    ])->log('Payment Successful for user_id:' . $paymentLog->user_id . ' amount:' . $amount_after_fee);

            } else {
                activity('Instamojo')
                    ->withProperties([
                        'ip' => $request->ip()
                    ])->log('Payment not successful');
                die();
            }
        } else {
            activity('Instamojo')
                ->withProperties([
                    'ip' => $request->ip()
                ])->log('MAC mismatch ERROR');
            die();
        }
    }


}
