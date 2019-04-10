<?php

namespace App\Http\Controllers;

use App\Transaction;
use Session;
use Auth;
use Validator;
use App\User;
use App\PaymentLog;
use App\PaymentMethod;
use Illuminate\Http\Request;

class PaywantController extends Controller
{
    private $payment_method_id = 9;
    private $paywant_api_key;
    private $paywant_api_secret;
    const PAYWANT_URL = 'http://api.paywant.com/gateway.php';
    const PAYMENT_TYPES = [
        '1' => 'Mobil Odeme',
        '2' => 'Kredi Karti',
        '3' => 'Banka (Havale/Eft/Atm)',
        '4' => 'Turk Telekom Odeme (TTNET Odeme)',
        '5' => 'Mikrocard',
        '6' => 'CashU',
    ];

    public function __construct()
    {
        $this->paywant_api_key = PaymentMethod::where(['config_key' => 'paywant_api_key'])->first()->config_value;
        $this->paywant_api_secret = PaymentMethod::where(['config_key' => 'paywant_api_secret'])->first()->config_value;
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

        return view('payments.paywant');
    }


    public function store(Request $request)
    {

        $minimum_deposit_amount = getOption('minimum_deposit_amount');
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $minimum_deposit_amount
        ]);

        if ($validator->fails()) {
            return redirect('payment/add-funds/paywant')
                ->withErrors($validator)
                ->withInput();
        }

        // Store payment log attempt into the database first
        // Also useful to get verify secrets
        $paymentLogSecret = bcrypt(Auth::user()->id . time() . rand(1, 99999));
        $user_id = Auth::user()->id;

        // Create payment logs
        $createLog = PaymentLog::create([
            'currency_code' => strtoupper(getOption('currency_code')),
            'details' => "NOT COMPLETED YET",
            'total_amount' => $request->input('amount'),
            'payment_method_id' => $this->payment_method_id,
            'user_id' => $user_id
        ]);

        $api_key = $this->paywant_api_key;
        $api_secret = $this->paywant_api_secret;
        $api_user_id = Auth::user()->id;
        $api_email = Auth::user()->email;
        $returnData = Auth::user()->email; // you must be send Email, beacuse you not using basket system. Only balance charge.
        $ip_address = $request->ip();
        $hash = hash_hmac('sha256', "$returnData|$api_email|$api_user_id" . $api_key, $api_secret, true);
        $paywant_hash = base64_encode($hash);

        $params = array(
            'apiKey' => $api_key,
            'hash' => $paywant_hash,
            'returnData' => $returnData,
            'userEmail' => $api_email,
            'userIPAddress' => $ip_address,
            'userID' => $api_user_id,
            'proApi' => 'true',
            'productData' => [
                'name' => $request->input('amount') . ' Bakiye Yukleme - ' . $createLog->id,
                'amount' => ($request->input('amount') * 100),
                'commissionType' => 2,
                'extraData' => $createLog->id,
            ]
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::PAYWANT_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $jsonDecode = json_decode($response, false);
        if ($err) {
            Session::flash('alert', __('messages.payment_failed') . $jsonDecode->Message);
            Session::flash('alertClass', 'danger no-auto-close');
            //paywant sistem hata sayfasına yonlendir
            return redirect('/payment/add-funds/paywant');
        } else {
            if ($jsonDecode->Status == 100) {
                // Ortak odeme sayfasina yonlendir
				if(!strpos($jsonDecode->Message,"https"))
					$jsonDecode->Message = str_replace("http","https",$jsonDecode->Message);
				
                return redirect()->away($jsonDecode->Message);
            } else {
                Session::flash('alert', __('messages.payment_failed') . $jsonDecode->Message);
                Session::flash('alertClass', 'danger no-auto-close');
                //paywant sistem hata sayfasına yonlendir
                return redirect('/payment/add-funds/paywant');
            }
        }
        curl_close($curl);
    }

    public function paywantNotify(Request $req) {
        if (
            $req->request->get('SiparisID') == '' ||
            $req->request->get('ExtraData') == '' ||
            $req->request->get('Status') == '' ||
            $req->request->get('OdemeKanali') == '' ||
            $req->request->get('OdemeTutari') == '' ||
            $req->request->get('NetKazanc') == '' ||
            $req->request->get('ReturnData') == '' ||
            $req->request->get('UrunTutari') == '' ||
            $req->request->get('UserID') == '' ||
            $req->request->get('Hash') == '') {
            activity('paywant')
                ->withProperties(['ip' => $req->ip])
                ->log('Post Data Missing.');
            die();
        }

        $extraData = $req->request->get('ExtraData');
        $paymentLog = PaymentLog::where(['id' => $extraData])->first();

        // If payment log is found then it means
        // the request is same which was sent
        if (!is_null($paymentLog)) {

            $log_id = $req->request->get('ExtraData');
            $ap_status = $req->request->get('Status');
            $order_id = $req->request->get('SiparisID');
            $payment_type = $req->request->get('OdemeKanali');
            $p_type = self::PAYMENT_TYPES[$payment_type];
            $ap_totalamount = $req->request->get('OdemeTutari');
            $ap_netamount = $req->request->get('NetKazanc');
            $returnData = $req->request->get('ReturnData');
            $load_amount = $req->request->get('UrunTutari');
            $user_id = $req->request->get('UserID');
            $hash = $req->request->get('Hash');

 			$user = User::find($user_id);

            $api_key = $this->paywant_api_key;
            $api_secret = $this->paywant_api_secret;
            // $api_user_id = $user_id; 
            // $api_email = $user->email; 
            // $returnData = $payment_secret;
            
			$new_hash = base64_encode(hash_hmac('sha256',"$order_id|$log_id|$user_id|$returnData|$ap_status|$payment_type|$ap_totalamount|$ap_netamount".$api_key,$api_secret,true));

            if($new_hash != $hash){
                activity('paywant')
                    ->withProperties([
                        'ip' => $req->ip()
                    ])->log('Hash MisMatch');
                die("hash mismatch");
            }
			
			if($paymentLog->details != "NOT COMPLETED YET"){
				activity('paywant')
                    ->withProperties([
                        'ip' => $req->ip()
                    ])->log('Payment Made Already');
                die("payment made already");
			}
				

            $data = [
                'status' => $ap_status,
                'order_id' => $order_id,
                'payment_type' => $p_type,
                'toplam_tutar' => $ap_totalamount,
                'toplam_kazanc' => $ap_netamount,
                'yukleme_tutari' => $load_amount,
                'hash' => $hash,
                'user_id' => $user_id,
            ];


            if ($ap_status == 100) {
                // Payment successful and update payment log
                PaymentLog::where(['id' => $log_id])->update([
                    'details' => json_encode($data),
                ]);
                // Create Transaction logs
                Transaction::create([
                    'amount' => $paymentLog->total_amount,
                    'payment_method_id' => $this->payment_method_id,
                    'user_id' => $user_id
                ]);

              
                $user->funds = $user->funds + $paymentLog->total_amount;
                $user->save();

                activity('paywant')
                    ->withProperties([
                        'ip' => $req->ip()
                    ])->log('Payment Loaded successfully for user_id:' . $paymentLog->user_id . ' amount:' . $ap_netamount);
                die("OK");
            }
        }else{
             activity('paywant')
                    ->withProperties([
                        'ip' => $req->ip()
                    ])->log('Payment Log Not Found');
                die("Payment Log Not Found");
        }
    }

}
