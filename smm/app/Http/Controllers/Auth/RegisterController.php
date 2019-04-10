<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Auth;

use App\PaymentMethod;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        config(["no-captcha.sitekey" => getOption('recaptcha_public_key')]);
        config(["no-captcha.secret" => getOption('recaptcha_private_key')]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => ['required', new CaptchaRule],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $paymentMethods = PaymentMethod::where(['config_key' => null, 'status' => 'ACTIVE', 'is_disabled_default' => 0])->groupBy('slug')->get()->pluck('id')->toArray();
        $payment_methods = '';
        if (!empty($paymentMethods)) {
            $payment_methods = implode(',', $paymentMethods);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'skype_id' => $data['skype_id'],
            'enabled_payment_methods' => $payment_methods,
            'password' => bcrypt($data['password']),
        ]);
    }
}
