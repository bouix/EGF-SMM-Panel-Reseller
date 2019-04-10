<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Order;
use App\Package;
use App\User;
use App\UserPackagePrice;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index(Request $request)
    {
        // Check if 'action' parameter is in request
        $validator = Validator::make($request->all(), [
            'action' => 'required'
        ]);
        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->all();
            return response()->json($response);
        }

        // Build parameters and call appropriate sub function
        $params = [];

        ###############################################
        ################ Add Order ####################
        ###############################################
        if (strtolower($request->input('action')) == 'add') {

            // Validate if all parameters are provided in add request
            $validator = Validator::make($request->all(), [
                'package' => 'required|numeric',
                'quantity' => 'required|numeric',
                'link' => 'required',
            ]);
            if ($validator->fails()) {
                $response['errors'] = $validator->errors()->all();
                return response()->json($response);
            }

            $params['package'] = $request->input('package');
            $params['quantity'] = $request->input('quantity');
            $params['link'] = $request->input('link');
            $params['custom_data'] = $request->input('custom_data') ?? '';

            $response = $this->add($params);
            return response()->json($response);

        }
        ###############################################
        ################ Fetch Status #################
        ###############################################
        elseif (strtolower($request->input('action')) == 'status') {

            // Validate if all parameters are provided in add request
            $validator = Validator::make($request->all(), [
                'order' => 'required',
            ]);
            if ($validator->fails()) {
                $response['errors'] = $validator->errors()->all();
                return response()->json($response);
            }
            $params['order'] = $request->input('order');

            $response = $this->status($params);
            return response()->json($response);
        }
        ###############################################
        ################ balance check ################
        ###############################################
        elseif ((strtolower($request->input('action')) == 'balance')) {
            $response['balance'] = Auth::user()->funds;
            $response['currency'] = getOption('currency_code', true);
            return response()->json($response);
        }
        ###############################################
        ################ Packages list ################
        ###############################################
        elseif ((strtolower($request->input('action')) == 'packages')) {
            $response = [];
            $packages = Package::where([
                'packages.status' => 'ACTIVE',
            ])->orderBy('service_id')->get();
            if (!$packages->isEmpty()) {
                foreach ($packages as $package) {
                    $type = $package->custom_comments == 1 ? 'custom_data' : 'default';
                    $response[] = [
                        'id' => $package->id,
                        'name' => $package->name,
                        'type' => $type
                    ];
                }
            }
            return response()->json($response);
        }

        return response()->json(['errors' => ['Incorrect request']]);
    }

    public function add($params)
    {
        $response = [
            'errors' => ''
        ];

        $package = Package::findOrfail($params['package']);
        $quantity = $params['quantity'];

        // if quantity is less than minimum
        if ($quantity < $package->minimum_quantity) {
            $response['errors'] = ['Please specify at least minimum quantity.'];
            return $response;
        }

        // if quantity is greater than maximum
        if ($quantity > $package->maximum_quantity) {
            $response['errors'] = ['Please specify less than or equal to maximum quantity'];
            return $response;
        }

        // Custom comments count validation
        if ($package->custom_comments) {
            $comments = $params['custom_data'];
            if ($comments != '') {
                $comments_arr = preg_split('/\r\n|\r|\n/', $comments);
                $total_comments = count($comments_arr);

                // If greater then quantity
                if ($total_comments > $quantity) {
                    $response['errors'] = ['You have added more comments than required quantity'];
                    return $response;
                }

                // if less then quantity
                if ($total_comments < $quantity) {
                    $response['errors'] = ['You have added less comments than required quantity'];
                    return $response;
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
            $response['errors'] = ['You do not have enough funds to Place order.'];
            return $response;
        }

        $custom_data = '';
        // if package have custom comments option
        if ($package->custom_comments) {
            $custom_data = preg_replace("/\r\n|\r|\n/", PHP_EOL, $params['custom_data']);
        }

        $order = Order::create([
            'price' => $price,
            'quantity' => $quantity,
            'package_id' => $package->id,
            'user_id' => Auth::user()->id,
            'api_id' => $package->preferred_api_id,
            'link' => $params['link'],
            'source' => 'API',
            'custom_comments' => $custom_data
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

        return $response;
    }

    public function status($params)
    {

        $response = [
            'errors' => ''
        ];

        $order = Order::where(['id' => $params['order'], 'user_id' => Auth::user()->id])->first();
        if (is_null($order)) {
            $response['errors'] = ['Order Not found'];
            return $response;
        } else {

            // delete errors key
            unset($response['errors']);

            $response['status'] = $order->status;
            $response['start_counter'] = $order->start_counter;
            $response['remains'] = $order->remains;
        }

        return $response;
    }

}