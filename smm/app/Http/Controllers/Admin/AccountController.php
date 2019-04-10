<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    public function showSettings()
    {
        return view('admin.account-settings');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old' => 'required',
            'password' => 'required|min:6',
            'name' => 'required'
        ]);

        if (!Hash::check($request->input('old'), Auth::user()->password)) {
            return view('settings')
                ->withErrors(['old' => __('messages.confirm_password_did_not_match')]);
        } elseif ($request->input('password') != $request->input('password_confirmation')) {
            return view('settings')
                ->withErrors(['password' => __('messages.confirm_password_did_not_match')]);
        }

        User::where(['id' => Auth::user()->id])->update([
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name')
        ]);

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/account/settings');
    }

}
