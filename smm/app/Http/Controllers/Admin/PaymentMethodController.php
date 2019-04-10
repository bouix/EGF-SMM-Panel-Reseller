<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PaymentMethodController extends Controller
{

    public function index()
    {
        $paymentMethods = PaymentMethod::where(['config_key' => null])->groupBy('slug')->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $configOptions = PaymentMethod::where(['slug' => $paymentMethod->slug])->whereNotNull('config_key')->get();
        return view('admin.payment-methods.edit', compact('paymentMethod', 'configOptions'));
    }


    public function update(Request $request, $id)
    {

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->status = $request->input('status');
        $paymentMethod->is_disabled_default = is_null($request->input('is_disabled_default')) ? 0 : 1;
        $paymentMethod->save();

        $config_key = $request->input('config_key');
        $config_value = $request->input('config_value');
        $rows = [];
        for ($i = 0; $i < count($config_key); $i++) {
            $rows[] = [
                'name' => $paymentMethod->name,
                'slug' => $paymentMethod->slug,
                'config_key' => $config_key[$i],
                'config_value' => $config_value[$i],
            ];
        }
        PaymentMethod::where(['slug' => $paymentMethod->slug])->whereNotNull('config_key')->delete();
        // Insert multiple
        DB::table('payment_methods')->insert($rows);

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/payment-methods/'.$id.'/edit');
    }

}
