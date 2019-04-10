<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Controllers\Admin;

use App\ApiRequestParam;
use App\Package;
use App\User;
use App\UserPackagePrice;
use Session;
use App\API;
use App\Order;
use Carbon\Carbon;
use App\ApiMapping;
use GuzzleHttp\Client;
use App\ApiResponseLog;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AutomateController extends Controller
{
    private $order_statuses = [];

    public function __construct()
    {
        $this->order_statuses = config('constants.ORDER_STATUSES');
    }

    public function listApi()
    {
        $apis = API::all();
        return view('admin.automate.api-list', compact('apis'));
    }

    public function addApi()
    {
        return view('admin.automate.api-add');
    }

    public function storeApi(Request $request)
    {

        mpc_m_c($request->server('SERVER_NAME'));
        $this->validate($request, [
            'name' => 'required',
            'order_end_point' => 'required|url',
            'order_method' => 'required',
            'order_key' => 'required',
            'order_key_type' => 'required',
            'order_key_value' => 'required',
            'order_success_response' => 'required|json',
            'status_end_point' => 'required|url',
            'status_key' => 'required',
            'status_key_type' => 'required',
            'status_key_value' => 'required',
            'order_id_key' => 'required',
            'start_counter_key' => 'required',
            'status_key_equal' => 'required',
            'remains_key' => 'required',
            'process_all_order' => 'required',
            'status_success_response' => 'required|json',
        ]);

        $api = API::create([
            'name' => $request->input('name'),
            'order_end_point' => $request->input('order_end_point'),
            'order_method' => $request->input('order_method'),
            'order_success_response' => str_replace('\t', '', $request->input('order_success_response')),
            'status_end_point' => $request->input('status_end_point'),
            'status_method' => $request->input('status_method'),
            'order_id_key' => $request->input('order_id_key'),
            'start_counter_key' => $request->input('start_counter_key'),
            'status_key' => $request->input('status_key_equal'),
            'remains_key' => $request->input('remains_key'),
            'process_all_order' => $request->input('process_all_order'),
            'status_success_response' => str_replace('\t', '', $request->input('status_success_response')),
        ]);

        // Order place Parameters
        $order_keys = $request->input('order_key');
        $order_key_values = $request->input('order_key_value');
        $order_key_types = $request->input('order_key_type');

        for ($i = 0; $i < count($order_keys); $i++) {
            ApiRequestParam::create([
                'param_key' => trim($order_keys[$i]),
                'param_value' => trim($order_key_values[$i]),
                'param_type' => trim($order_key_types[$i]),
                'api_type' => 'order',
                'api_id' => $api->id,
            ]);
        }

        // Order status Parameters
        $status_keys = $request->input('status_key');
        $status_key_values = $request->input('status_key_value');
        $status_key_types = $request->input('status_key_type');

        for ($i = 0; $i < count($status_keys); $i++) {
            ApiRequestParam::create([
                'param_key' => trim($status_keys[$i]),
                'param_value' => trim($status_key_values[$i]),
                'param_type' => trim($status_key_types[$i]),
                'api_type' => 'status',
                'api_id' => $api->id,
            ]);
        }


        Session::flash('alert', __('messages.created'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/automate/api-list');
    }

    public function editApi($id)
    {
        $api = API::findOrFail($id);
        $content = '';
        $apiMapping = ApiMapping::where(['api_id' => $id])->pluck('api_package_id', 'package_id')->toArray();
        $apiRequestParams = ApiRequestParam::where(['api_id' => $id])->get();
        $packages = Package::where(['status' => 'ACTIVE'])->orderBy('service_id')->get();

        return view('admin.automate.api-edit', compact('api', 'content', 'apiRequestParams', 'packages', 'apiMapping'));
    }

    public function updateApi($id, Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'order_end_point' => 'required|url',
            'order_key' => 'required',
            'order_method' => 'required',
            'order_key_type' => 'required',
            'order_key_value' => 'required',
            'order_success_response' => 'required|json',
            'status_end_point' => 'required|url',
            'status_key' => 'required',
            'status_key_type' => 'required',
            'status_key_value' => 'required',
            'order_id_key' => 'required',
            'start_counter_key' => 'required',
            'status_key_equal' => 'required',
            'remains_key' => 'required',
            'process_all_order' => 'required',
            'status_success_response' => 'required|json',
        ]);

        API::findOrFail($id)->update([
            'name' => $request->input('name'),
            'order_end_point' => $request->input('order_end_point'),
            'order_method' => $request->input('order_method'),
            'order_success_response' => str_replace('\t', '', $request->input('order_success_response')),
            'status_end_point' => $request->input('status_end_point'),
            'status_method' => $request->input('status_method'),
            'order_id_key' => $request->input('order_id_key'),
            'start_counter_key' => $request->input('start_counter_key'),
            'status_key' => $request->input('status_key_equal'),
            'remains_key' => $request->input('remains_key'),
            'process_all_order' => $request->input('process_all_order'),
            'status_success_response' => str_replace('\t', '', $request->input('status_success_response')),
        ]);

        ApiRequestParam::where(['api_id' => $id])->delete();

        // Place order params
        $order_keys = $request->input('order_key');
        $order_key_values = $request->input('order_key_value');
        $order_key_types = $request->input('order_key_type');

        for ($i = 0; $i < count($order_keys); $i++) {
            ApiRequestParam::create([
                'param_key' => trim($order_keys[$i]),
                'param_value' => trim($order_key_values[$i]),
                'param_type' => trim($order_key_types[$i]),
                'api_type' => 'order',
                'api_id' => $id,
            ]);
        }

        // Get status params
        $status_keys = $request->input('status_key');
        $status_key_values = $request->input('status_key_value');
        $status_key_types = $request->input('status_key_type');

        for ($i = 0; $i < count($status_keys); $i++) {
            ApiRequestParam::create([
                'param_key' => trim($status_keys[$i]),
                'param_value' => trim($status_key_values[$i]),
                'param_type' => trim($status_key_types[$i]),
                'api_type' => 'status',
                'api_id' => $id,
            ]);
        }

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/automate/api/' . $id . '/edit');

    }

    public function deleteApi($id)
    {
        $api = API::findOrFail($id);
        try {
            // Check if it is NOT used in
            // Packages
            // Orders
            // Logs
            // Then delete API
            if (!Package::where(['preferred_api_id' => $api->id])->exists()
                && !Order::where(['api_id' => $api->id])->exists()
                && !ApiResponseLog::where(['api_id' => $api->id])->exists()) {

                // Delete API mappings
                ApiMapping::where(['api_id' => $api->id])->delete();

                // Delete API parameters
                ApiRequestParam::where(['api_id' => $api->id])->delete();

                $api->delete();
            } // Could not be deleted as it is used in other tables
            else {
                Session::flash('alert', __('messages.api_have_logs'));
                Session::flash('alertClass', 'danger');
                return redirect('/admin/automate/api-list');
            }

        } catch (QueryException $ex) {
            Session::flash('alert', __('messages.api_have_logs'));
            Session::flash('alertClass', 'danger');
            return redirect('/admin/automate/api-list');
        }

        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/automate/api-list');
    }

    public function storeMapping($id, Request $request)
    {
        $packages = $request->input('package_id');
        $apiPackages = $request->input('api_package_id');

        $insert = [];
        for ($i = 0; $i < count($packages); $i++) {
            // Check if api_package_id is not empty or zero
            if ($apiPackages[$i] != '' && $apiPackages[$i] != "0") {
                $insert[] = [
                    'package_id' => trim($packages[$i]),
                    'api_package_id' => trim($apiPackages[$i]),
                    'api_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        if (!empty($insert)) {
            // Delete all previous mapping
            ApiMapping::where(['api_id' => $id])->delete();
            \DB::table('api_mappings')->insert($insert);
        }

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');

        return redirect('admin/automate/api/' . $id . '/edit');

    }

    public function sendOrdersIndex()
    {
        return view('admin.automate.send-orders-index');
    }

    public function sendOrdersIndexData()
    {
        $orders = Order::with('user', 'package.service')->where(['status' => 'PENDING', 'api_order_id' => null]);
        return datatables()
            ->of($orders)
            ->addColumn('api', 'admin.automate.send-orders-api-select')
            ->addColumn('action', 'admin.automate.send-orders-action-buttons')
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $order->link . '" target="_blank">' . str_limit($order->link, 30) . '</a>';
            })
            ->rawColumns(['action', 'api', 'link'])
            ->toJson();

    }

    public function sendOrderToApi(Request $request)
    {
        $api = API::find($request->input('api_id'));
        if (is_null($api)) {
            return response()->json([
                'success' => false,
                'message' => 'Selected API is not configured yet!',
                'css_class' => 'alert-warning'
            ]);
        }

        // Get package mapping ids
        $apiMapping = ApiMapping::where(['api_id' => $api->id, 'package_id' => $request->input('package_id')])->first();
        if (is_null($apiMapping)) {
            return response()->json([
                'success' => false,
                'message' => 'package_id is not mapped with API Package ID.',
                'css_class' => 'alert-warning'
            ]);
        }

        $order = Order::find($request->input('id'));

        // Build api request parameters
        $params = [];
        $apiRequestParams = ApiRequestParam::where(['api_id' => $api->id, 'api_type' => 'order'])->get();
        if (!$apiRequestParams->isEmpty()) {

            foreach ($apiRequestParams as $row) {
                if ($row->param_type == 'custom') {
                    $params[$row->param_key] = $row->param_value;
                } else {

                    // If column is package id then assign package id value in api mapping
                    if ($row->param_value == 'package_id') {
                        $params[$row->param_key] = $apiMapping->api_package_id;
                    } elseif ($row->param_value == 'custom_comments') {
                        $package = Package::find($order->package_id);
                        if ($package->custom_comments) {
                            $params[$row->param_key] = $order->{$row->param_value};
                        }
                    } else {
                        $params[$row->param_key] = $order->{$row->param_value};
                    }
                }
            }

            // create new client and make call
            $client = new Client();
            try {

                // if Method is GET then change request key in Guzzle
                $param_key = 'form_params';
                if ($api->order_method === 'GET') {
                    $param_key = 'query';
                }

                $res = $client->request($api->order_method, $api->order_end_point, [
                    $param_key => $params,
                    'headers' => ['Accept' => 'application/json'],
                ]);

                if ($res->getStatusCode() === 200) {

                    $resp = $res->getBody()->getContents();

                    $success_response = array_cast_recursive(json_decode($api->order_success_response));

                    // Response keys are equal to success response?
                    if (empty(array_diff_key_recursive(array_cast_recursive(json_decode($resp)), $success_response))) {

                        ApiResponseLog::create([
                            'order_id' => $request->input('id'),
                            'api_id' => $api->id,
                            'response' => $resp
                        ]);


                        // Get orderID column from API response
                        $r = json_decode($resp);
                        Order::find($request->input('id'))->update([
                            'api_id' => $api->id,
                            'api_order_id' => $r->{$api->order_id_key},
                            'status' => 'INPROGRESS'
                        ]);

                        return response()->json([
                            'success' => true,
                            'message' => 'Success! Order placed successfully!',
                            'css_class' => 'alert-success'
                        ]);

                    } else {

                        ApiResponseLog::create([
                            'order_id' => $request->input('id'),
                            'api_id' => $api->id,
                            'response' => $resp
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Failed! Please see response logs.',
                            'css_class' => 'alert-danger'
                        ]);

                    }
                }
            } catch (ClientException $e) {

                ApiResponseLog::create([
                    'order_id' => $request->input('id'),
                    'api_id' => $api->id,
                    'response' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed! Please see response logs.',
                    'css_class' => 'alert-danger'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Error! Something Went Wrong!',
            'css_class' => 'alert-danger'
        ]);
    }

    public function getResponseLogsIndex()
    {

        return view('admin.automate.response-logs');
    }

    public function getResponseLogsIndexData()
    {
        $logs = ApiResponseLog::with('api');
        return datatables()
            ->of($logs)
            ->editColumn('response', function ($log) {
                return "<code>" . $log->response . "</code>";
            })
            ->rawColumns(['response'])
            ->toJson();
    }

    public function getOrderStatusIndex()
    {
        return view('admin.automate.get-order-status-index');
    }

    public function getOrderStatusIndexData()
    {
        $orders = Order::with('user', 'package.service', 'api')
            ->whereNotIn('status', ['COMPLETED', 'CANCELLED', 'PARTIAL', 'REFUNDED'])
            ->where('api_order_id', '!=', null);

        return datatables()
            ->of($orders)
            ->addColumn('action', 'admin.automate.get-order-status-action-buttons')
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $order->link . '" target="_blank">' . str_limit($order->link, 30) . '</a>';
            })
            ->rawColumns(['action', 'link'])
            ->toJson();
    }

    public function getOrderStatusFromAPI(Request $request)
    {

        $order = Order::findOrFail($request->input('id'));
        $api = API::find($order->api_id);

        // Build api request parameters
        $params = [];
        $apiRequestParams = ApiRequestParam::where(['api_id' => $api->id, 'api_type' => 'status'])->get();
        if (!$apiRequestParams->isEmpty()) {

            foreach ($apiRequestParams as $row) {
                if ($row->param_type === 'custom') {
                    $params[$row->param_key] = $row->param_value;
                } else {
                    $params[$row->param_key] = $order->{$row->param_value};
                }
            }
            $params[$api->order_id_key] = $order->api_order_id;

            // create new client and make call
            $client = new Client();
            try {

                $param_key = 'form_params';
                if ($api->status_method === 'GET') {
                    $param_key = 'query';
                }

                $res = $client->request($api->status_method, $api->status_end_point, [
                    $param_key => $params,
                    'headers' => ['Accept' => 'application/json'],
                ]);

                if ($res->getStatusCode() === 200) {

                    $resp = $res->getBody()->getContents();

                    $success_response = array_cast_recursive(json_decode($api->status_success_response));

                    // Response keys are equal to success response?
                    if (empty(array_diff_key_recursive(array_cast_recursive(json_decode($resp)), $success_response))) {

                        ApiResponseLog::create([
                            'order_id' => $request->input('id'),
                            'api_id' => $api->id,
                            'response' => $resp
                        ]);

                        // Get orderID column from API response
                        $r = array_cast_recursive(json_decode($resp));

                        // 'status' key is present in array?
                        if (array_key_exists($api->status_key, $r)) {

                            if (strtoupper(trim($r[$api->status_key])) == 'COMPLETED' || strtoupper(trim($r[$api->status_key])) == 'COMPLETE') {
                                Order::find($request->input('id'))->update([
                                    'status' => 'COMPLETED',
                                    'start_counter' => $r[$api->start_counter_key],
                                    'remains' => $r[$api->remains_key],
                                ]);
                                return response()->json([
                                    'success' => true,
                                    'message' => 'Order completed.',
                                    'css_class' => 'alert-success'
                                ]);
                            } elseif (strtoupper(trim($r[$api->status_key])) == 'PENDING'
                                || strtoupper(trim($r[$api->status_key])) == 'INPROGRESS'
                                || strtoupper(trim($r[$api->status_key])) == 'IN_PROGRESS'
                                || strtoupper(trim($r[$api->status_key])) == 'IN-PROGRESS'
                                || strtoupper(trim($r[$api->status_key])) == 'IN PROGRESS'
                                || strtoupper(trim($r[$api->status_key])) == 'PROGRESS') {
                                // do nothing with status but update the start_count
                                Order::find($request->input('id'))->update([
                                    'start_counter' => $r[$api->start_counter_key],
                                    'remains' => $r[$api->remains_key],
                                ]);
                            } elseif (strtoupper(trim($r[$api->status_key])) == 'CANCEL' ||
                                strtoupper(trim($r[$api->status_key])) == 'CANCELLED' ||
                                strtoupper(trim($r[$api->status_key])) == "CANCELED") {

                                if ($api->process_all_order) {
                                    $user = User::find($order->user_id);
                                    $user->funds = $user->funds + $order->price;
                                    $user->save();

                                    Order::find($request->input('id'))->update([
                                        'start_counter' => $r[$api->start_counter_key],
                                        'remains' => $r[$api->remains_key],
                                        'status' => 'CANCELLED',
                                    ]);

                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Order Cancelled.',
                                        'css_class' => 'alert-info'
                                    ]);
                                }

                                return response()->json([
                                    'success' => false,
                                    'message' => 'Order Cancelled, Please Mark Order Cancel Manually',
                                    'css_class' => 'alert-info'
                                ]);

                            } elseif (strtoupper(trim($r[$api->status_key])) == 'REFUND' ||
                                strtoupper(trim($r[$api->status_key])) == 'REFUNDED') {

                                if ($api->process_all_order) {
                                    $user = User::find($order->user_id);
                                    $user->funds = $user->funds + $order->price;
                                    $user->save();

                                    Order::find($request->input('id'))->update([
                                        'start_counter' => $r[$api->start_counter_key],
                                        'remains' => $r[$api->remains_key],
                                        'status' => 'REFUNDED',
                                    ]);

                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Order Refunded.',
                                        'css_class' => 'alert-info'
                                    ]);
                                }

                                return response()->json([
                                    'success' => false,
                                    'message' => 'Order Refunded, Please Mark Order Refund Manually',
                                    'css_class' => 'alert-info'
                                ]);

                            } elseif (in_array(strtoupper(trim($r[$api->status_key])), ['PARTIAL', 'PARTIALLY', 'PARTIALLY COMPLETED', 'PARTIAL COMPLETE'])) {

                                if (isset($r[$api->remains_key]) && $r[$api->remains_key] > 0) {

                                    $remains = $r[$api->remains_key];
                                    $quantity = $order->quantity;
                                    $orderPrice = $order->price;
                                    $user = User::find($order->user_id);

                                    // Get price per item, if special price is not set then, just get standard price
                                    $price_per_item = Package::find($order->package_id)->price_per_item;
                                    $userPackagePrice = UserPackagePrice::where(['user_id' => $order->user_id, 'package_id' => $order->package_id])->first();
                                    if (!is_null($userPackagePrice)) {
                                        $price_per_item = $userPackagePrice->price_per_item;
                                    }

                                    if ($remains < $quantity) {
                                        // Order price to .00 decimal points
                                        $refundAmount = (float)$price_per_item * $remains;
                                        $refundAmount = number_format($refundAmount, 2, '.', '');

                                        if ($refundAmount > 0) {
                                            // decrease amount in order price
                                            $orderPrice = $orderPrice - $refundAmount;

                                            // Refund partial to user account
                                            $user->funds = $user->funds + $refundAmount;
                                            $user->save();

                                            Order::find($order->id)->update([
                                                'start_counter' => $r[$api->start_counter_key],
                                                'status' => 'PARTIAL',
                                                'remains' => $r[$api->remains_key],
                                                'price' => $orderPrice
                                            ]);
                                        }
                                    }

                                }

                                return response()->json([
                                    'success' => true,
                                    'message' => 'Order Partial Complete.',
                                    'css_class' => 'alert-success'
                                ]);

                            } elseif (!in_array(strtoupper(trim($r[$api->status_key])), ['REFUNDED', 'REFUND'])
                                && in_array(strtoupper(trim($r[$api->status_key])), $this->order_statuses)) {

                                // do nothing with status but update the start_count
                                Order::find($request->input('id'))->update([
                                    'start_counter' => $r[$api->start_counter_key],
                                    'status' => strtoupper(trim($r[$api->status_key])),
                                    'remains' => $r[$api->remains_key],
                                ]);

                            }
                        }

                        return response()->json([
                            'success' => false,
                            'message' => 'Order Status: ' . $r[$api->status_key],
                            'css_class' => 'alert-info'
                        ]);

                    } else {

                        ApiResponseLog::create([
                            'order_id' => $request->input('id'),
                            'api_id' => $api->id,
                            'response' => $resp
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Failed! Please see response logs.',
                            'css_class' => 'alert-danger'
                        ]);

                    }
                }
            } catch (ClientException $e) {

                ApiResponseLog::create([
                    'order_id' => $request->input('id'),
                    'api_id' => $api->id,
                    'response' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed! Please see response logs.',
                    'css_class' => 'alert-danger'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed! Please see response logs.',
            'css_class' => 'alert-danger'
        ]);
    }

    public function changeReseller(Request $request)
    {
        $order = Order::findOrFail($request->input('id'));
        $order->api_id = null;
        $order->api_order_id = null;
        $order->status = 'PENDING';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order is ready to send to another reseller, in ' . __('menus.automate') . ' -> ' . __('menus.send_orders'),
            'css_class' => 'alert-success'
        ]);
    }
}
