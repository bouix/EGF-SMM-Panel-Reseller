<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Console\Commands;

use App\API;
use App\ApiRequestParam;
use App\ApiResponseLog;
use App\Order;
use App\Package;
use App\User;
use App\UserPackagePrice;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;

class CheckOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order status sent to APIs';

    private $order_statuses = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->order_statuses = config('constants.ORDER_STATUSES');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::whereNotIn('status', ['PENDING', 'CANCELLED', 'COMPLETED', 'PARTIAL', 'REFUNDED'])->where('api_order_id', '!=', null)->inRandomOrder()->limit(15)->get();
        if (!$orders->isEmpty()) {

            foreach ($orders as $order) {
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

                                // Get orderID column from API response
                                $r = array_cast_recursive(json_decode($resp));

                                // 'status' key is present in array?
                                if (array_key_exists($api->status_key, $r)) {

                                    if (strtoupper(trim($r[$api->status_key])) == 'COMPLETED' || strtoupper(trim($r[$api->status_key])) == 'COMPLETE') {
                                        Order::find($order->id)->update([
                                            'status' => 'COMPLETED',
                                            'start_counter' => $r[$api->start_counter_key],
                                            'remains' => $r[$api->remains_key],
                                        ]);
                                    } elseif (strtoupper(trim($r[$api->status_key])) == 'PENDING'
                                        || strtoupper(trim($r[$api->status_key])) == 'INPROGRESS'
                                        || strtoupper(trim($r[$api->status_key])) == 'IN_PROGRESS'
                                        || strtoupper(trim($r[$api->status_key])) == 'IN-PROGRESS'
                                        || strtoupper(trim($r[$api->status_key])) == 'IN PROGRESS'
                                        || strtoupper(trim($r[$api->status_key])) == 'PPROCESSING'
                                        || strtoupper(trim($r[$api->status_key])) == 'PROGRESS') {
                                        // do nothing with status but update the start_count
                                        Order::find($order->id)->update([
                                            'start_counter' => $r[$api->start_counter_key],
                                            'remains' => $r[$api->remains_key],
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

                                                    // do nothing with status but update the start_count
                                                    Order::find($order->id)->update([
                                                        'start_counter' => $r[$api->start_counter_key],
                                                        'status' => strtoupper(trim($r[$api->status_key])),
                                                        'remains' => $r[$api->remains_key],
                                                        'price' => $orderPrice
                                                    ]);
                                                }
                                            }

                                        }

                                    } elseif (in_array(strtoupper(trim($r[$api->status_key])), [
                                        'CANCEL',
                                        'CANCELLED',
                                        'CANCELED',
                                    ])) {

                                        if ($api->process_all_order) {
                                            $user = User::find($order->user_id);
                                            $user->funds = $user->funds + $order->price;
                                            $user->save();

                                            Order::find($order->id)->update([
                                                'start_counter' => $r[$api->start_counter_key],
                                                'remains' => $r[$api->remains_key],
                                                'status' => 'CANCELLED',
                                            ]);
                                        }
                                    } elseif (in_array(strtoupper(trim($r[$api->status_key])), [
                                        'REFUND',
                                        'REFUNDED'
                                    ])) {

                                        if ($api->process_all_order) {
                                            $user = User::find($order->user_id);
                                            $user->funds = $user->funds + $order->price;
                                            $user->save();

                                            Order::find($order->id)->update([
                                                'start_counter' => $r[$api->start_counter_key],
                                                'remains' => $r[$api->remains_key],
                                                'status' => 'REFUNDED',
                                            ]);
                                        }
                                    } elseif (in_array(strtoupper(trim($r[$api->status_key])), $this->order_statuses)) {

                                        Order::find($order->id)->update([
                                            'start_counter' => $r[$api->start_counter_key],
                                            'status' => strtoupper(trim($r[$api->status_key])),
                                            'remains' => $r[$api->remains_key],
                                        ]);

                                    }
                                }
                            }
                            ApiResponseLog::create([
                                'order_id' => $order->id,
                                'api_id' => $api->id,
                                'response' => $resp
                            ]);
                        }
                    } catch
                    (ClientException $e) {

                        ApiResponseLog::create([
                            'order_id' => $order->id,
                            'api_id' => $api->id,
                            'response' => $e->getMessage()
                        ]);

                    }
                }
            }
        }
    }
}
