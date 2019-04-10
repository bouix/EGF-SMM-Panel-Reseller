<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Controllers\Admin;

use App\API;
use App\Order;
use App\Package;
use App\User;
use App\UserPackagePrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    private $order_statuses = [];

    public function __construct()
    {
        $this->order_statuses = config('constants.ORDER_STATUSES');
    }

    public function index()
    {
        return view('admin.orders.index');
    }

    public function indexData()
    {
        $orders = Order::with('user', 'package.service');
        return datatables()
            ->of($orders)
            ->addColumn('action', 'admin.orders.index-buttons')
            ->addColumn('bulk', function ($order) {
                $disabled = '';
                if (in_array(strtoupper($order->status), ['COMPLETED', 'PARTIAL', 'REFUNDED', 'CANCELLED'])) {
                    $disabled = 'disabled';
                }
                return "<input type='checkbox' $disabled class='input-sm row-checkbox' name='order_id[$order->id]' value='$order->id'>";
            })
            ->editColumn('price', function ($order) {
                return getOption('currency_symbol') . number_format($order->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('start_counter', function ($order) {
                return "<input type='text' style='width: 60px;' readonly class='form-control input-sm row-edit' value='$order->start_counter' name='start_counter[$order->id]'>";
            })
            ->editColumn('remains', function ($order) {
                return "<input type='text' style='width: 60px;' readonly class='form-control input-sm row-edit' value='$order->remains' name='remains[$order->id]'>";
            })
            ->editColumn('status', function ($order) {
                $html = "<select class='form-control row-edit' readonly name='status[$order->id]'>";
                foreach ($this->order_statuses as $status) {
                    if ($status == strtoupper($order->status)) {
                        $html .= "<option selected value='$status'>$status</option>";
                    } else {
                        $html .= "<option value='$status'>$status</option>";
                    }
                }
                return $html;
            })
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="'.getOption('anonymizer').$order->link.'" target="_blank">'.str_limit($order->link,30).'</a>';
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>".$order->created_at."</span>";
            })
            ->rawColumns(['action', 'bulk', 'start_counter', 'remains', 'status','link','created_at'])
            ->toJson();
    }

    public function indexFilter($status){
        return view('admin.orders.index', compact('status'));
    }

    public function indexFilterData($status)
    {
        $orders = Order::with('user', 'package.service')->where(['status' => strtoupper($status)]);
        return datatables()
            ->of($orders)
            ->addColumn('action', 'admin.orders.index-buttons')
            ->addColumn('bulk', function ($order) {
                $disabled = '';
                if (in_array(strtoupper($order->status), ['COMPLETED', 'PARTIAL', 'REFUNDED', 'CANCELLED'])) {
                    $disabled = 'disabled';
                }
                return "<input type='checkbox' $disabled class='input-sm row-checkbox' name='order_id[$order->id]' value='$order->id'>";
            })
            ->editColumn('price', function ($order) {
                return getOption('currency_symbol') . number_format($order->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('start_counter', function ($order) {
                return "<input type='text' style='width: 60px;' readonly class='form-control input-sm row-edit' value='$order->start_counter' name='start_counter[$order->id]'>";
            })
            ->editColumn('remains', function ($order) {
                return "<input type='text' style='width: 60px;' readonly class='form-control input-sm row-edit' value='$order->remains' name='remains[$order->id]'>";
            })
            ->editColumn('status', function ($order) {
                $html = "<select class='form-control row-edit' readonly name='status[$order->id]'>";
                foreach ($this->order_statuses as $status) {
                    if ($status == strtoupper($order->status)) {
                        $html .= "<option selected value='$status'>$status</option>";
                    } else {
                        $html .= "<option value='$status'>$status</option>";
                    }
                }
                return $html;
            })
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="'.getOption('anonymizer').$order->link.'" target="_blank">'.str_limit($order->link,30).'</a>';
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>".$order->created_at."</span>";
            })
            ->rawColumns(['action', 'bulk', 'start_counter', 'remains', 'status','link','created_at'])
            ->toJson();
    }

    public function create()
    {
        return redirect('/admin/orders');
    }

    public function store(Request $request)
    {
        return redirect('/admin/orders');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $apis = API::all();
        return view('admin.orders.show', compact(
            'order',
            'apis'
        ));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $apis = API::all();
        return view('admin.orders.edit', compact(
            'order',
            'apis'
        ));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = User::find($order->user_id);
        $orderPrice = $order->price;

        // If order is cancelled or refund then refund complete amount
        if ($request->input('status') == 'CANCELLED' || $request->input('status') == 'REFUNDED') {
            $user->funds = $user->funds + $orderPrice;
            $user->save();
        } // Order partial complete refund remaining amount
        elseif ($request->input('status') == 'PARTIAL') {
            $remains = ($request->input('remains') > 1) ? $request->input('remains') : 1;
            $quantity = $order->quantity;

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
                }
            }

        }


        $api_id = !empty($request->input('api_id')) ? $request->input('api_id') : null;
        $status = !is_null($request->input('status')) ? $request->input('status') : $order->status; // some disabled status will not be submitted in $_POST

        $order->status = $status;
        $order->start_counter = $request->input('start_counter');
        $order->remains = $request->input('remains');
        $order->api_id = $api_id;
        $order->price = $orderPrice;
        $order->link = $request->input('link');
        $order->custom_comments = $request->input('custom_comments');
        $order->save();

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/orders/'.$id.'/edit');

    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if (in_array(strtoupper($order->status),['COMPLETED','PARTIAL'])) {
            Session::flash('alert', __('messages.order_completed_cannot_delete'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect('/admin/orders');
        } elseif ($order->status === 'Pending') {
            Session::flash('alert', __('messages.order_processing_cannot_delete'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect('/admin/orders');
        }

        $order->delete();
        Session::flash('alert', __('messages.deleted'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/orders');
    }

    public function completeOrder($id)
    {
        $order = Order::find($id);
        $success = false;
        if (!is_null($order)) {
            $order->status = 'COMPLETED';
            $order->save();
            $success = true;
        }

        return response()->json(['success' => $success, 'status' => $order->status]);
    }

    public function bulkUpdate(Request $request)
    {
        $orderIds = $request->input('order_id');
        $startCounters = $request->input('start_counter');
        $remains = $request->input('remains');
        $statuses = $request->input('status');

        foreach ($orderIds as $id) {
            $order = Order::find($id);
            $user = User::find($order->user_id);
            $orderPrice = $order->price;

            // New status is cancelled or refund then return amount to user funds
            if (strtoupper($statuses[$id]) == 'CANCELLED' || strtoupper($statuses[$id]) == 'REFUNDED') {
                $user->funds = $user->funds + $orderPrice;
                $user->save();
            } elseif (strtoupper($statuses[$id]) == 'PARTIAL') {

                if ($remains[$id] < 1) {
                    continue;
                }

                // Get price per item, if special price is not set then, just get standard price
                $price_per_item = Package::find($order->package_id)->price_per_item;
                $userPackagePrice = UserPackagePrice::where(['user_id' => $order->user_id, 'package_id' => $order->package_id])->first();
                if (!is_null($userPackagePrice)) {
                    $price_per_item = $userPackagePrice->price_per_item;
                }

                if ($remains[$id] < $order->quantity) {
                    // Order price to .00 decimal points
                    $refundAmount = (float)$price_per_item * $remains[$id];
                    $refundAmount = number_format($refundAmount, 2, '.', '');

                    if ($refundAmount > 0) {
                        // decrease amount in order price
                        $orderPrice = $orderPrice - $refundAmount;

                        // Refund partial to user account
                        $user->funds = $user->funds + $refundAmount;
                        $user->save();
                    }
                }

            }

            $order->start_counter = $startCounters[$id];
            $order->remains = $remains[$id];
            $order->status = $statuses[$id];
            $order->price = $orderPrice;
            $order->save();

        }
        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/orders');

    }
}