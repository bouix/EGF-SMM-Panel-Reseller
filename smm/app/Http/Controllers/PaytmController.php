<?php

namespace App\Http\Controllers;

use App\PaymentLog;
use App\Transaction;
use App\User;
use Session;
use App\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaytmController extends Controller
{
    private $payment_method_id = 8;
    private $paytm_email_imap;
    private $paytm_email;
    private $paytm_email_password;
    private $paytm_indian_rupees_valued_1_usd;

    public function __construct()
    {
        $this->paytm_email_imap = PaymentMethod::where(['config_key' => 'paytm_email_imap_address'])->first()->config_value;
        $this->paytm_email = PaymentMethod::where(['config_key' => 'paytm_email'])->first()->config_value;
        $this->paytm_email_password = PaymentMethod::where(['config_key' => 'paytm_email_password'])->first()->config_value;
        $this->paytm_indian_rupees_valued_1_usd = PaymentMethod::where(['config_key' => 'paytm_indian_rupees_valued_1_usd'])->first()->config_value;
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

        return view('payments.paytm');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'transaction_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if (strlen($request->input('transaction_id')) < 10) {
            // Payment fail error show payment failed error
            Session::flash('alert', __('messages.payment_failed'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();
        }

        if (strlen($request->input('transaction_id')) > 50) {
            // Payment fail error show payment failed error
            Session::flash('alert', __('messages.payment_failed'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();
        }

        // Check if transaction id is found already so means
        // Funds got added already no need to further add it.
        if(Transaction::where(['details' => $request->input('transaction_id')])->exists()){
            Session::flash('alert', __('Funds Loaded Already With This transaction ID'));
            Session::flash('alertClass', 'info no-auto-close');
            return redirect()->back();
        }

        $amount = $request->input('amount');
        $connection = imap_open(trim($this->paytm_email_imap), trim($this->paytm_email), trim($this->paytm_email_password));

        //Connection established to the mail server, now search the transaction
        $matchTxn = imap_search($connection, 'TEXT "'.$request->input('transaction_id') . '"',SE_FREE, "UTF-8");

        if ($matchTxn !== false) {
            //get message id
            $a = var_export($matchTxn, true);
            $data = $a;
            $whatIWant = substr($data, strpos($data, ">") + 1);
            $to = ", )";
            $c = chop($whatIWant, $to);
            $d = str_replace(",", "", $c);
            $e = preg_replace('/\s+/', '', $d);

            //only if the certain amount was sent
            $headerInfo = imap_headerinfo($connection, $e);
            
            if (!strpos($headerInfo->subject, "Rs.$amount")) {
                // Amount mismatch show payment failed error
                // Payment fail error show payment failed error
                Session::flash('alert', __('messages.payment_failed'));
                Session::flash('alertClass', 'danger no-auto-close');
                return redirect()->back();
            }

            //Check if panel currency is USD
            if(strtoupper(getOption('currency_code')) == 'USD'){
                $amount = ($request->input('amount')/$this->paytm_indian_rupees_valued_1_usd);
            }


            // Add funds to user's account
            Transaction::create([
                'amount' => $amount,
                'payment_method_id' => $this->payment_method_id,
                'user_id' => Auth::user()->id,
                'details' => $request->input('transaction_id')
            ]);

            $user = User::find(Auth::user()->id);
            $user->funds = $user->funds + $amount;
            $user->save();

            imap_close($connection);

            Session::flash('alert', __('messages.payment_success'));
            Session::flash('alertClass', 'success');
            return redirect()->back();

        } else {

            // Payment fail error show payment failed error
            Session::flash('alert', __('messages.payment_failed'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();

        }
    }

}
