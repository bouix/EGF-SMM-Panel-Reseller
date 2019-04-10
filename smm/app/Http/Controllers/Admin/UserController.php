<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use DB;
use App\Package;
use App\PaymentMethod;
use App\Transaction;
use App\User;
use App\UserPackagePrice;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

    public function indexData()
    {
        $users = User::all();
        return datatables()
            ->of($users)
            ->editColumn('funds', function ($user) {
                return getOption('currency_symbol') . number_format($user->funds,2, getOption('currency_separator'), '');
            })
            ->addColumn('action', 'admin.users.index-buttons')
            ->toJson();
    }

    public function create()
    {
        $paymentMethods = PaymentMethod::where(['config_key' => null, 'status' => 'ACTIVE'])->groupBy('slug')->get();
        return view('admin.users.create', compact('paymentMethods'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        $payment_methods = '';
        if (!is_null($request->input('payment_methods'))) {
            $payment_methods = implode(',', $request->input('payment_methods'));
        }

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'role' => $request->input('role'),
            'funds' => $request->input('funds'),
            'skype_id' => $request->input('skype_id'),
            'enabled_payment_methods' => $payment_methods,
            'password' => bcrypt($request->input('password'))
        ]);

        Session::flash('alert', __('messages.created'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/users/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $packages = Package::where(['status' => 'ACTIVE'])->orderBy('service_id')->get();
        $userPackagePrices = UserPackagePrice::where(['user_id' => $id])->pluck('price_per_item', 'package_id')->toArray();

        $paymentMethods = PaymentMethod::where(['config_key' => null])->groupBy('slug')->get();
        $enabled_payment_methods = [];
        if ($user->enabled_payment_methods != '') {
            $enabled_payment_methods = explode(',', $user->enabled_payment_methods);
        }

        return view('admin.users.edit', compact(
            'user',
            'paymentMethods',
            'enabled_payment_methods',
            'userPackagePrices',
            'packages'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        try {
            $user = User::findOrFail($id);

            $payment_methods = '';
            if (!is_null($request->input('payment_methods'))) {
                $payment_methods = implode(',', $request->input('payment_methods'));
            }

            $user->name = $request->input('name');
            $user->status = $request->input('status');
            $user->role = $request->input('role');
            $user->funds = $request->input('funds');
            if ($request->filled('password')) {
                if (strlen($request->input('password')) < 6) {
                    return redirect()
                        ->back()
                        ->withErrors(['password' => 'Minimum Length Should be 6']);
                }
                $user->password = bcrypt($request->input('password'));
            }
            $user->enabled_payment_methods = $payment_methods;
            $user->skype_id = $request->input('skype_id');
            $user->save();

        } catch (QueryException $ex) {
            Session::flash('alert', __('messages.email_already_used'));
            Session::flash('alertClass', 'danger');
            return redirect('/admin/users/' . $id . '/edit');
        }

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success no-auto-close');
        return redirect('/admin/users/'.$id.'/edit');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        try {
            $user->delete();
        } catch (QueryException $ex) {
            Session::flash('alert', __('messages.user_have_orders'));
            Session::flash('alertClass', 'danger');
            return redirect('/admin/users');
        }

        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/users');
    }

    public function getFundsLoadHistory(Request $request)
    {
        return view('admin.transaction-history.index');
    }

    public function getFundsLoadHistoryData()
    {
        $transactions = Transaction::with('paymentMethod', 'user');
        return datatables()
            ->of($transactions)
            ->editColumn('amount', function ($transaction) {
                return getOption('currency_symbol') . number_format($transaction->amount,2, getOption('currency_separator'), '');
            })
            ->toJson();

    }

    public function addFunds(Request $request, $id)
    {
        $this->validate($request, [
            'payment_method_id' => 'required',
            'fund' => 'required',
            'details' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->funds = $user->funds + $request->input('fund');
        $user->save();

        // Create Transaction logs
        Transaction::create([
            'amount' => $request->input('fund'),
            'payment_method_id' => $request->input('payment_method_id'),
            'user_id' => $id,
            'details' => $request->input('details')
        ]);

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('admin/users/' . $id . '/edit');
    }

    public function packageSpecialPrices($id, Request $request)
    {

        $packageIds = $request->input('package_id');
        $pricePerItems = $request->input('price_per_item');
        $minimumQuanties = $request->input('minimum_quantity');

        if(empty($packageIds))
            return redirect()->back();

        $insertRows = [];
        foreach ($packageIds as $packageId) {

            // Regular user minimum order Price to .00 decimal points
            $min_regular_price = (float)$pricePerItems[$packageId] * $minimumQuanties[$packageId];
            $min_regular_price = number_format($min_regular_price, 2, '.', '');

            if ($min_regular_price > 0) {
                $insertRows[] = [
                    'user_id' => $id,
                    'package_id' => $packageId,
                    'price_per_item' => $pricePerItems[$packageId]
                ];
            }
        }

        UserPackagePrice::where(['user_id' => $id])->delete();
        if (!empty($insertRows)) {
            DB::table('user_package_prices')->insert($insertRows);
        }

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('admin/users/' . $id . '/edit');
    }
}
