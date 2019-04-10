<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Controllers;

use DateTimeZone;
use Session;
use App\User;
use App\Transaction;
use App\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    public function showSettings()
    {
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('settings', compact('tzlist'));
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

        mpc_m_c($request->server('SERVER_NAME'));
        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/account/settings');
    }

    public function updateConfig(Request $request)
    {
        User::where(['id' => Auth::user()->id])->update([
            'timezone' => $request->input('timezone'),
        ]);
        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/account/settings');
    }

    public function generateKey()
    {
        $api_token = bcrypt(Auth::user()->email . time() . env('API_SECRET_PHRASE'));
        User::where(['id' => Auth::user()->id])->update([
            'api_token' => $api_token,
        ]);

        return redirect('/account/settings');
    }

    public function getFundsLoadHistory()
    {
        return view('transaction-history.index');
    }

    public function getFundsLoadHistoryData()
    {

        $transactions = Transaction::with('paymentMethod')->where(['user_id' => Auth::user()->id]);
        return datatables()
            ->of($transactions)
            ->editColumn('amount', function ($transaction) {
                return getOption('currency_symbol') . number_format($transaction->amount, 2, getOption('currency_separator'), '');
            })
            ->toJson();
    }
}
