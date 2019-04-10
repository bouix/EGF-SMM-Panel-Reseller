<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Package;
use App\User;
use App\UserPackagePrice;
use Session;
use App\Service;
use App\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyModuleSubscriptionEnabled;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyModuleSubscriptionEnabled::class);
    }

    public function index()
    {
        return view('admin.subscriptions.index');
    }

    public function indexData()
    {
        $subscriptions = Subscription::with('package.service');

        return datatables()
            ->of($subscriptions)
            ->editColumn('link', function ($subscription) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $subscription->link . '" target="_blank">' . str_limit($subscription->link, 30) . '</a>';
            })
            ->editColumn('price', function ($subscription) {
                return getOption('currency_symbol') . number_format($subscription->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('posts', function ($subscription) {
                $orders = Order::where(['subscription_id' => $subscription->id])->count();
                return $orders . '/' . $subscription->posts;
            })
            ->editColumn('status', function ($subscription) {
                return "<span class='status-".strtolower($subscription->status)."'>".$subscription->status."</span>";
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>".$order->created_at."</span>";
            })
            ->addColumn('action', 'admin.subscriptions.index-buttons')
            ->rawColumns(['link', 'action','status','created_at'])
            ->toJson();
    }

    public function indexFilter($status)
    {
        return view('admin.subscriptions.index', compact('status'));
    }

    public function indexFilterData($status)
    {
        $subscriptions = Subscription::with('package.service')->where(['status' => strtoupper($status)]);

        return datatables()
            ->of($subscriptions)
            ->editColumn('link', function ($subscription) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $subscription->link . '" target="_blank">' . str_limit($subscription->link, 30) . '</a>';
            })
            ->editColumn('price', function ($subscription) {
                return getOption('currency_symbol') . number_format($subscription->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('posts', function ($subscription) {
                $orders = Order::where(['subscription_id' => $subscription->id])->count();
                return $orders . '/' . $subscription->posts;
            })
            ->editColumn('status', function ($subscription) {
                return "<span class='status-".strtolower($subscription->status)."'>".$subscription->status."</span>";
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>".$order->created_at."</span>";
            })
            ->addColumn('action', 'admin.subscriptions.index-buttons')
            ->rawColumns(['link', 'action','status','created_at'])
            ->toJson();
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'link' => 'required',
        ]);

        Subscription::where(['id' => $id])->update([
            'link' => $request->input('link')
        ]);

        Session::flash('alert', __('messages.updated'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/subscriptions/' . $id . '/edit');
    }

    public function cancel(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status = 'CANCELLED';
        $subscription->save();

        $user = User::find($subscription->user_id);
        $user->funds = $user->funds + $subscription->price;
        $user->save();

        Session::flash('alert', __('messages.subscription_cancelled'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/subscriptions');
    }

    public function stop(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status = 'STOPPED';
        $subscription->save();

        $total = Order::where(['subscription_id' => $subscription->id])->sum('price');
        $userRefund = $subscription->price - $total;

        $user = User::find($subscription->user_id);
        $user->funds = $user->funds + $userRefund;
        $user->save();

        Session::flash('alert', __('messages.subscription_stopped'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/subscriptions');
    }


    public function orders($id)
    {
        $subscription = Subscription::findOrFail($id);
        $orders = Order::where(['subscription_id' => $id])->get();
        return view('admin.subscriptions.orders.index', compact('subscription', 'orders'));
    }

    public function storeOrder(Request $request, $id)
    {
        $this->validate($request, [
            'link' => 'required',
            'start_counter' => 'required',
            'remains' => 'required',
        ]);

        $subscription = Subscription::findOrFail($id);
        $package = Package::findOrFail($subscription->package_id);

        // Calculate Price
        $userPackagePrices = UserPackagePrice::where(['user_id' => $subscription->user_id])->pluck('price_per_item', 'package_id')->toArray();
        $package_price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;

        // Order price to .00 decimal points
        $price = (float)$package_price * $subscription->quantity;
        $price = number_format($price, 2, '.', '');

        Order::create([
            'price' => $price,
            'quantity' => $subscription->quantity,
            'package_id' => $package->id,
            'user_id' => $subscription->user_id,
            'link' => $request->input('link'),
            'start_counter' => $request->input('start_counter'),
            'remains' => $request->input('remains'),
            'status' => 'COMPLETED',
            'subscription_id' => $id,
        ]);

        $subscription->status = 'ACTIVE';
        $totalOrders = Order::where(['subscription_id' => $subscription->id])->count();
        if ($totalOrders >= $subscription->posts) {
            $subscription->status = 'COMPLETED';
        }
        $subscription->save();

        Session::flash('alert', __('messages.order_placed'));
        Session::flash('alertClass', 'success');
        return redirect('/admin/subscriptions/' . $id . '/orders');

    }
}
