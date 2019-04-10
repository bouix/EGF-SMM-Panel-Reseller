<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\Config;
use App\User;
use DateTimeZone;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{

    public function edit(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $options = Config::all()->pluck('value','name');
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('admin.system-settings', compact('options', 'tzlist'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'app_name' => 'required',
            'currency_symbol' => 'required',
            'currency_code' => 'required',
            'date_format' => 'required',
            'home_page_description' => 'required',
            'recaptcha_private_key' => 'required',
            'minimum_deposit_amount' => 'required',
            'home_page_meta' => 'required',
            'notify_email' => 'required'
        ]);

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');
            $fileArray = array('logo' => $file);
            $rules = array(
                'logo' => 'mimes:png|required|' // max 10000kb
            );
            $validator = Validator::make($fileArray, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
                return redirect()
                    ->back()
                    ->withErrors(['logo' => $errors['logo']]);

            } else {
                $logo = Storage::putFile('images', $request->file('logo'));
                setOption('logo', $logo);
            }

        }

        if ($request->hasFile('banner')) {

            $file = $request->file('banner');
            $fileArray = array('banner' => $file);
            $rules = array(
                'banner' => 'mimes:png,jpg,jpeg|required|' // max 10000kb
            );
            $validator = Validator::make($fileArray, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
                return redirect()
                    ->back()
                    ->withErrors(['banner' => $errors['banner']]);

            } else {
                $banner = Storage::putFile('images', $request->file('banner'));
                setOption('banner', $banner);
            }

        }

        setOption('app_name', $request->input('app_name'));
        setOption('currency_symbol', $request->input('currency_symbol'));
        setOption('currency_code', $request->input('currency_code'));
        setOption('date_format', $request->input('date_format'));
        setOption('home_page_description', $request->input('home_page_description'));
        setOption('recaptcha_public_key', $request->input('recaptcha_public_key'));
        setOption('recaptcha_private_key', $request->input('recaptcha_private_key'));
        setOption('minimum_deposit_amount', $request->input('minimum_deposit_amount'));
        setOption('home_page_meta', $request->input('home_page_meta'));
        setOption('module_support_enabled', $request->input('module_support_enabled'));
        setOption('module_api_enabled', $request->input('module_api_enabled'));
        setOption('module_subscription_enabled', $request->input('module_subscription_enabled'));
        setOption('theme_color', $request->input('theme_color'));
        setOption('background_color', $request->input('background_color'));
        setOption('language', $request->input('language'));
        setOption('display_price_per', $request->input('display_price_per'));
        setOption('admin_layout', $request->input('admin_layout'));
        setOption('user_layout', $request->input('user_layout'));
        setOption('panel_theme', $request->input('panel_theme'));
        setOption('anonymizer', $request->input('anonymizer'));
        setOption('front_page', $request->input('front_page'));
        setOption('show_service_list_without_login', $request->input('show_service_list_without_login'));
        setOption('notify_email', $request->input('notify_email'));
        setOption('currency_separator', $request->input('currency_separator'));
        setOption('timezone', $request->input('timezone'));

        Session::flash('alert',  __('messages.updated_logout_needed'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/system/settings');
    }

}
