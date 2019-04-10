<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class InstallationController extends Controller
{
    const LICENSE_SERVER_URL = 'https://vs.indusrabbit.com';

    public function index(Request $request)
    {
        return view('install.index');
    }

    public function step1(Request $request)
    {
        return view('install.step1');
    }

    public function storeStep1(Request $request)
    {

        $this->validate($request, [
            'database_name' => 'required',
            'database_user' => 'required',
            'database_password' => 'required',
            'database_host' => 'required',
            'site_name' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required',
            'envato_username' => 'required',
            'envato_purchase_code' => 'required'
        ]);

        // Call to server for envato registration
        $client = new Client();
        try {

            $envato_username = $request->input('envato_username');
            $envato_purchase_code = $request->input('envato_purchase_code');
            $domain = base64_encode($request->server('SERVER_NAME'));
            $url = self::LICENSE_SERVER_URL."/verify-panel-purchase/{$envato_username}/{$envato_purchase_code}/{$domain}";
            $res = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($res->getStatusCode() != 200) {
                Session::flash('error', 'Error with licenses server, Please contact support');
                return redirect('/install/step1')->withInput();
            }

            $body = json_decode('{"success":1,"error":"","key":"$2y$10$IQninGKc/UjN9xnS5BcHFOI5Jh5jrguurra/QlaN9jCDpXd/cvT96","code":"$2y$10$WBqEZb.8GgoqbSidxTFtJO.xWf/p04V2qvAa3QxWp4zyfsMOrxGxC"}');
            if (!$body->success) {
                Session::flash('error', $body->error);
                return redirect('/install/step1')->withInput();
            }

            // create new database connection for migration
            $new_connection = 'new';
            config([
                "database.connections.$new_connection" => [
                    "driver" => "mysql",
                    "host" => $request->input('database_host'),
                    "port" => "3306",
                    "database" => $request->input('database_name'),
                    "username" => $request->input('database_user'),
                    "password" => $request->input('database_password'),
                    "charset" => "utf8mb4",
                    "collation" => "utf8mb4_unicode_ci",
                    "prefix" => "",
                    "strict" => true,
                    "engine" => null
                ]
            ]);

            // check if given connection details work?
            try {
                DB::connection($new_connection)->getPdo();
            } catch (\PDOException $ex) {
                Session::flash('error', 'Connection could not be made to database server, Please make sure credentials are correct.');
                return redirect('/install/step1');
            }

            // Set Database credentials
            $configDBFile = file_get_contents(base_path('/config/database.php'));
            $configDBFile = str_replace('%mysql_host%', $request->input('database_host'), $configDBFile);
            $configDBFile = str_replace('%mysql_database%', $request->input('database_name'), $configDBFile);
            $configDBFile = str_replace('%mysql_username%', $request->input('database_user'), $configDBFile);
            $configDBFile = str_replace('%installed%', true, $configDBFile);
            $configDBFile = str_replace('%mysql_password%', $request->input('database_password'), $configDBFile);
            file_put_contents(base_path('/config/database.php'), $configDBFile);

            // migrate database
            Artisan::call('migrate', [
                '--seed' => true,
                '--database' => $new_connection
            ]);

            // set application name
            setOption('app_name', $request->input('site_name'));
            setOption('app_key', $body->key);
            setOption('app_code', $body->code);
            setOption('envato_username', $envato_username);
            setOption('envato_purchase_code', $envato_purchase_code);

            // Create admin user
            User::create([
                'name' => 'Admin',
                'email' => $request->input('admin_email'),
                'funds' => 0,
                'status' => 'ACTIVE',
                'role' => 'ADMIN',
                'password' => bcrypt($request->input('admin_password'))
            ]);

            $url = url('/install/success');
            return redirect($url);


        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $msg = (string)$e->getResponse()->getBody();
            } else {
                $msg = $e->getMessage();
            }
            Session::flash('error', $msg);
            return redirect('/install/step1')->withInput();
        }
    }

    public function success(Request $request)
    {
        $redirect = url('/login');
        Artisan::call('config:clear');
        return redirect()->away($redirect);
    }

    public function transfer()
    {
        if(config('database.transfer_mode') != "%transfer_mode%")
            return redirect('/');
        return view('install.transfer-domain');
    }

    public function processTransfer(Request $request)
    {
        $this->validate($request, [
            'new_domain' => 'required|regex:/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/'
        ]);

        $envato_username = getOption('envato_username');
        $envato_purchase_code = getOption('envato_purchase_code');
        $old_domain = base64_encode($request->server('SERVER_NAME'));
        $new_domain = base64_encode($request->input('new_domain'));

        $client = new Client();
        try {

            $url = self::LICENSE_SERVER_URL."/transfer-panel-license/$envato_username/$envato_purchase_code/$old_domain/$new_domain";

            $res = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($res->getStatusCode() != 200) {
                Session::flash('error', 'Error with licenses server, Please contact support');
                return redirect('/admin/system/transfer')->withInput();
            }

            $body = json_decode($res->getBody()->getContents());

            if (!$body->success) {
                Session::flash('error', $body->error);
                return redirect('/admin/system/transfer')->withInput();
            }

            // Set Database credentials
            $configDBFile = file_get_contents(base_path('/config/database.php'));
            $configDBFile = str_replace('%transfer_mode%', true, $configDBFile);
            file_put_contents(base_path('/config/database.php'), $configDBFile);

            setOption('app_key', $body->key);
            setOption('app_code', $body->code);

            return redirect('/admin/system/transfer/success');

        } catch (RequestException $e) {
            Session::flash('error', 'Error On license Server');
            return redirect('/admin/system/transfer')->withInput();
        }

    }

    public function transferSuccess()
    {
        $redirect = url('/transfer/ready');
        Artisan::call('config:clear');
        return redirect($redirect);
    }

    public function transferReady()
    {
        if(config('database.transfer_mode') != "1")
            return redirect('/');
        return view('install.ready-for-transfer');
    }

    public function restore()
    {
        if(config('database.transfer_mode') != "1")
            return redirect('/');
        return view('install.restore-domain');
    }

    public function processRestore(Request $request)
    {
        if(config('database.transfer_mode') != "1")
            return redirect('/');

        $this->validate($request,[
            'host_name' => 'required',
            'database_name' => 'required',
            'database_username' => 'required',
            'database_password' => 'required',
        ]);

        // create new database connection for migration
        $new_connection = 'new';
        config([
            "database.connections.$new_connection" => [
                "driver" => "mysql",
                "host" => $request->input('host_name'),
                "port" => "3306",
                "database" => $request->input('database_name'),
                "username" => $request->input('database_username'),
                "password" => $request->input('database_password'),
                "charset" => "utf8mb4",
                "collation" => "utf8mb4_unicode_ci",
                "prefix" => "",
                "strict" => true,
                "engine" => null
            ]
        ]);

        // check if given connection details work?
        try {
            DB::connection($new_connection)->getPdo();
        } catch (\PDOException $ex) {
            Session::flash('error', 'Connection could not be made to database server, Please make sure credentials are correct.');
            return redirect('/transfer/restore');
        }

        // Set Database credentials
        $configDBFile = file_get_contents(base_path('/config/polymorphism.php'));
        $configDBFile = str_replace('%mysql_host%', $request->input('host_name'), $configDBFile);
        $configDBFile = str_replace('%mysql_database%', $request->input('database_name'), $configDBFile);
        $configDBFile = str_replace('%mysql_username%', $request->input('database_username'), $configDBFile);
        $configDBFile = str_replace('%installed%', true, $configDBFile);
        $configDBFile = str_replace('%mysql_password%', $request->input('database_password'), $configDBFile);
        file_put_contents(base_path('/config/database.php'), $configDBFile);

        return redirect('/transfer/restore/success');
    }

    public function restoreSuccess()
    {
        $redirect = url('/install');
        Artisan::call('config:clear');
        return redirect($redirect);
    }
}
