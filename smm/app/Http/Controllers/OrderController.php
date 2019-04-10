<?php namespace App\Http\Controllers;

/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
use App\User;
use Validator;
use App\Order;
use App\Package;
use App\Service;
use Carbon\Carbon;
use App\UserPackagePrice;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    public function index()
    {
        return view('orders.index');
    }

    public function indexData()
    {
        $orders = Order::with('package.service')->where(['orders.user_id' => Auth::user()->id]);
        return datatables()
            ->of($orders)
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $order->link . '" target="_blank">' . str_limit($order->link, 30) . '</a>';
            })
            ->editColumn('price', function ($order) {
                return getOption('currency_symbol') . number_format($order->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('status', function ($order) {
                return "<span class='status-" . strtolower($order->status) . "'>" . $order->status . "</span>";
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>" . $order->created_at . "</span>";
            })
            ->rawColumns(['link', 'status', 'created_at'])
            ->toJson();

    }

    public function indexFilter($status)
    {
        return view('orders.index', compact('status'));
    }

    public function indexFilterData($status)
    {
        $orders = Order::with('package.service')->where(['orders.user_id' => Auth::user()->id, 'status' => strtoupper($status)]);
        return datatables()
            ->of($orders)
            ->editColumn('link', function ($order) {
                return '<a rel="noopener noreferrer" href="' . getOption('anonymizer') . $order->link . '" target="_blank">' . str_limit($order->link, 30) . '</a>';
            })
            ->editColumn('price', function ($order) {
                return getOption('currency_symbol') . number_format($order->price, 2, getOption('currency_separator'), '');
            })
            ->editColumn('status', function ($order) {
                return "<span class='status-" . strtolower($order->status) . "'>" . $order->status . "</span>";
            })
            ->editColumn('created_at', function ($order) {
                return "<span class='no-word-break'>" . $order->created_at . "</span>";
            })
            ->rawColumns(['link', 'status', 'created_at'])
            ->toJson();
    }


    public function newOrder(Request $request)
    {
        mpc_m_c($request->server('SERVER_NAME'));
        $services = Service::where(['status' => 'ACTIVE', 'is_subscription_allowed' => 0])->get();
        $packages = Package::where(['status' => 'ACTIVE'])->get();
        return view('orders.new', compact('packages', 'services'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'package_id' => 'required',
            'quantity' => 'required|numeric',
            'link' => 'required',
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

        // Custom comments count validation
        if ($package->custom_comments) {
            $commnets = $request->input('custom_comments');
            if ($commnets != '') {
                $commnets_arr = preg_split('/\n/', $commnets);
                $total_comments = count($commnets_arr);

                // If greator then quantity
                if ($total_comments > $quantity) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['quantity' => __('messages.comments_are_more_than_quantity')]);
                }

                // if less then quantity
                if ($total_comments < $quantity) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['quantity' => __('messages.comments_are_less_than_quantity')]);
                }

            }
        }

        // Calculate Price
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        $package_price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;

        // Order price to .00 decimal points
        $price = (float)$package_price * $quantity;
        $price = number_format($price, 2, '.', '');

        // check if user have enough funds to make an order
        if (Auth::user()->funds < $price) {
            Session::flash('alert', __('messages.not_enough_funds'));
            Session::flash('alertClass', 'danger no-auto-close');
            return redirect()->back();
        }

        $order = Order::create([
            'price' => $price,
            'quantity' => $quantity,
            'package_id' => $package->id,
            'api_id' => $package->preferred_api_id,
            'user_id' => Auth::user()->id,
            'link' => $request->input('link'),
            'custom_comments' => $request->input('custom_comments'),
        ]);

        // Deduct user funds which he has used
        $user = User::find(Auth::user()->id);
        $user->funds = ($user->funds - $price);
        $user->save();

        // If preferred API is set then dispatch order to reseller panel
        if (!is_null($package->preferred_api_id)) {
            event(new OrderPlaced($order));
        }

        Session::flash('alert', __('messages.order_placed'));
        Session::flash('alertClass', 'success');
        return redirect('/order/new');

    }

    public function showMassOrderForm()
    {
        $packages = Package::where('status', 'ACTIVE')->orderBy('service_id')->get();
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        return view('orders.mass-order', compact('packages', 'userPackagePrices'));
    }

    public function storeMassOrder(Request $request)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        // User special prices if any
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();

        // Explode into each row
        $rows = explode(PHP_EOL, $request->input('content'));
        if (!empty($rows)) {
            $orders = []; // Bulk insert array
            $sumPrice = 0;
            foreach ($rows as $row) {
                // Explode order detail
                $order = explode('|', $row);
                // Check if contain all four attributes service_id|quantity|link
                if (count($order) === 3) {
                    $package_id = $order[0];
                    $quantity = $order[1];
                    $link = $order[2];
                    $package = Package::find($package_id);
                    if (!is_null($package)) {
                        // if quantity is greater than minimum and less than or equal to maximum
                        if ($quantity >= $package->minimum_quantity && $quantity <= $package->maximum_quantity) {
                            // Calculate Price
                            $package_price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;

                            // Order price to .00 decimal points
                            $price = (float)$package_price * $quantity;
                            $price = number_format($price, 2, '.', '');
                            if ($price > 0) {
                                $sumPrice += $price; // sumPrice for checking user funds.
                                $orders[] = [
                                    'price' => $price,
                                    'quantity' => $quantity,
                                    'package_id' => $package->id,
                                    'api_id' => $package->preferred_api_id,
                                    'user_id' => Auth::user()->id,
                                    'link' => $link,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }
                    }
                }
            }

            if (!empty($orders)) {

                // Check if user have enough funds to make orders
                if (Auth::user()->funds < $sumPrice) {
                    Session::flash('alert', __('messages.not_enough_funds'));
                    Session::flash('alertClass', 'danger no-auto-close');
                    return redirect()->back()->withInput();
                }

                Order::insert($orders);

                // Deduct user funds which he has used
                $user = User::find(Auth::user()->id);
                $user->funds = ($user->funds - $sumPrice);
                $user->save();

                Session::flash('alert', __('messages.order_placed'));
                Session::flash('alertClass', 'success');
                return redirect('/order/mass-order');
            }
        }

        Session::flash('alert', __('messages.something_went_wrong'));
        Session::flash('alertClass', 'danger no-auto-close');
        return redirect()->back()->withInput();
    }

    public function APIStoreOrder(Request $request)
    {

        $response = [
            'errors' => ''
        ];

        $validator = Validator::make($request->all(), [
            'package_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->all();
            return response()->json($response);
        }

        $package = Package::findOrfail($request->input('package_id'));
        $quantity = $request->input('quantity');

        // if quantity is less than minimum
        if ($quantity < $package->minimum_quantity) {
            $response['errors'] = ['Please specify at least minimum quantity.'];
            return response()->json($response);
        }

        // if quantity is greater than maximum
        if ($quantity > $package->maximum_quantity) {
            $response['errors'] = ['Please specify less than or equal to maximum quantity'];
            return response()->json($response);
        }

        // Custom comments count validation
        if ($package->custom_comments) {
            $commnets = $request->input('comments');
            if ($commnets != '') {
                $commnets_arr = preg_split('/\n/', $commnets);
                $total_comments = count($commnets_arr);

                // If greator then quantity
                if ($total_comments > $quantity) {
                    $response['errors'] = ['You have added more comments than required quantity'];
                    return response()->json($response);
                }

                // if less then quantity
                if ($total_comments < $quantity) {
                    $response['errors'] = ['You have added less comments than required quantity'];
                    return response()->json($response);
                }

            }
        }

        // Calculate Price
        // Calculate Price
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        $package_price = isset($userPackagePrices[$package->id]) ? $userPackagePrices[$package->id] : $package->price_per_item;

        // Order price to .00 decimal points
        $price = (float)$package_price * $quantity;
        $price = number_format($price, 2, '.', '');

        // check if user have enough funds to make an order
        if (Auth::user()->funds < $price) {
            $response['errors'] = ['You do not have enough funds to Place order.'];
            return response()->json($response);
        }

        $custom_comments = '';
        // if package have custom comments option
        if ($package->custom_comments) {
            $custom_comments = preg_replace("/\r\n|\r|\n/", PHP_EOL, $request->input('comments'));
        }

        $order = Order::create([
            'price' => $price,
            'quantity' => $quantity,
            'package_id' => $package->id,
            'user_id' => Auth::user()->id,
            'api_id' => $package->preferred_api_id,
            'link' => $request->input('link'),
            'source' => 'API',
            'custom_comments' => $custom_comments
        ]);

        // delete errors key
        unset($response['errors']);

        $response['order'] = $order->id;

        // Deduct user funds which he has used
        $user = User::find(Auth::user()->id);
        $user->funds = ($user->funds - $price);
        $user->save();

        // If preferred API is set then dispatch order to reseller panel
        if (!is_null($package->preferred_api_id)) {
            event(new OrderPlaced($order));
        }

        return response()->json($response);

    }

    public function APIGetOrderStatus(Request $request)
    {
        $response = [
            'errors' => ''
        ];

        $order = Order::where(['id' => $request->input('order'), 'user_id' => Auth::user()->id])->first();
        if (is_null($order)) {
            $response['errors'] = ['Order Not found'];
            return response()->json($response);
        } else {

            // delete errors key
            unset($response['errors']);

            $response['status'] = $order->status;
            $response['start_counter'] = $order->start_counter;
            $response['remains'] = $order->remains;
        }

        return response()->json($response);
    }

    public function getPackages($service_id)
    {
        $packages = Package::where(['service_id' => $service_id, 'status' => 'ACTIVE'])->get();
        $userPackagePrices = UserPackagePrice::where(['user_id' => Auth::user()->id])->pluck('price_per_item', 'package_id')->toArray();
        return view('orders.partial-packages', compact(
            'packages',
            'userPackagePrices'
        ));
    }


}
