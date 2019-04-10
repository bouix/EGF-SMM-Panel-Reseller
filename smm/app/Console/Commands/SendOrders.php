<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Console\Commands;

use App\API;
use App\ApiMapping;
use App\ApiRequestParam;
use App\ApiResponseLog;
use App\Order;
use App\Package;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SendOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Orders to Reseller Panels Automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::where(['status' => 'PENDING', 'api_order_id' => null])->where('api_id', '!=', null)->inRandomOrder()->limit(10)->get();

        if (!$orders->isEmpty()) {
            foreach ($orders as $order) {
                $api = API::find($order->api_id);
                // Get package mapping ids
                $apiMapping = ApiMapping::where(['api_id' => $api->id, 'package_id' => $order->package_id])->first();
                if (is_null($apiMapping)) {
                    continue;
                }

                // Build api request parameters
                $params = [];
                $apiRequestParams = ApiRequestParam::where(['api_id' => $api->id, 'api_type' => 'order'])->get();
                if (!$apiRequestParams->isEmpty()) {
                    foreach ($apiRequestParams as $row) {
                        if ($row->param_type === 'custom') {
                            $params[$row->param_key] = $row->param_value;
                        } else {

                            // If column is package id then assign package id value in api mapping
                            if ($row->param_value === 'package_id') {
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
                                // Get orderID column from API response
                                $r = json_decode($resp);
                                Order::find($order->id)->update([
                                    'api_id' => $api->id,
                                    'api_order_id' => $r->{$api->order_id_key},
                                    'status' => 'INPROGRESS'
                                ]);
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
