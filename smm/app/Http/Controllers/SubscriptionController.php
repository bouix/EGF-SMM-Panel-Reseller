<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Session;
use App\Package;
use App\Service;
use App\Subscription;
use App\UserPackagePrice;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyModuleSubscriptionEnabled;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyModuleSubscriptionEnabled::class);
    }

    public function index()
    {
        return view('subscriptions.index');
    }

    public function indexData()
    {
        $subscriptions = Subscription::with('package.service')
            ->where(['subscriptions.user_id' => Auth::user()->id]);

        return datatables()
            ->of($subscriptions)
            ->editColumn('link', function ($subscription) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $subscription->link . '" target="_blank">' . str_limit($subscription->link, 50) . '</a>';
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
            ->addColumn('action', 'subscriptions.index-buttons')
            ->rawColumns(['link', 'action','status','created_at'])
            ->toJson();
    }

    public function create(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $services = Service::where(['status' => 'ACTIVE', 'is_subscription_allowed' => 1])->get();
        return view('subscriptions.new', compact('services'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'package_id' => 'required',
            'quantity' => 'required|numeric',
            'link' => 'required',
            'posts' => 'required|numeric',
        ]);

        $package = Package::findOrfail($request->input('package_id'));
        $quantity = $request->input('quantity');

        // if quantity is less than minimum
        if ($quantity < $package->minimum_quantity) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['quantity' => __('messages.minimum_quantity')]);
        }

        // if quantity is greater than maximum
        if ($quantity > $package->maximum_quantity) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['quantity' => __('messages.maximum_quantity')]);
        }


        // Calculate Price
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        $package_price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;
        $posts = $request->input('posts');

        // Order price to .00 decimal points
        $price = (float) (($package_price * $quantity) * $posts);
        $price = number_format($price, 2, '.', '');

        // check if user have enough funds to make an order
        if (Auth::user()->funds < $price) {
            Session::flash('alert', __('messages.not_enough_funds'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();
        }

        Subscription::create([
            'quantity' => $quantity,
            'user_id' => Auth::user()->id,
            'package_id' => $package->id,
            'posts' => $posts,
            'link' => $request->input('link'),
            'price' => $price,
        ]);

        // Deduct user funds which he has used
        $user = User::find(Auth::user()->id);
        $user->funds = ($user->funds - $price);
        $user->save();

        Session::flash('alert', __('messages.order_placed'));
        Session::flash('alertClass', 'success');
        return redirect('/subscription/new');
    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        $orders = Order::where(['subscription_id' => $id])->get();
        return view('subscriptions.orders.index', compact('subscription', 'orders'));
    }


}
