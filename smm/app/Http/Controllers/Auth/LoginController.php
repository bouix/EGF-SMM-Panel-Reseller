<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Session;
use App\Config;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/order/new';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        config(["no-captcha.sitekey" => getOption('recaptcha_public_key')]);
        config(["no-captcha.secret" => getOption('recaptcha_private_key')]);
    }

    public function authenticated($request, $user)
    {

        if ($user->status === title_case('DEACTIVATED')) {
            Auth::logout();
            return redirect('/login')
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => __('messages.account_suspended'),
                ]);
        }

        $options = Config::pluck('value', 'name')->all();
        Session::put('options', $options);

        if ($user->role == 'ADMIN') {
            return redirect('/admin');
        }
        return redirect($this->redirectTo);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required',
            'password' => 'required'
        ];

        // if request have captcha then
        // include captcha validation rules
        if ($request->input('g-recaptcha-response') !== null) {
            $rules['g-recaptcha-response'] = ['required', new CaptchaRule];
        }
        $this->validate($request, $rules);
    }

}
