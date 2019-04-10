<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class UpdateController extends Controller
{

    const LICENSE_SERVER_URL = 'https://vs.indusrabbit.com';

    public function update(Request $request)
    {
        $licenseInfo = $this->getLicenseInfo();
        if (empty($licenseInfo)) {
            return redirect('/update/license-form');
        } else {

            $domain = base64_encode($request->server('SERVER_NAME'));
            $envato_username = $licenseInfo['envato_username'];
            $envato_purchase_code = $licenseInfo['envato_purchase_code'];

            $client = new Client();
            try {

                $url = self::LICENSE_SERVER_URL . "/verify-panel-purchase/{$envato_username}/{$envato_purchase_code}/{$domain}";
                $res = $client->request('GET', $url, [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);

                if ($res->getStatusCode() != 200) {
                    return 'Error with licenses server, Please contact support';
                } else {

                    $body = json_decode('{"success":1,"error":"","key":"$2y$10$IQninGKc/UjN9xnS5BcHFOI5Jh5jrguurra/QlaN9jCDpXd/cvT96","code":"$2y$10$WBqEZb.8GgoqbSidxTFtJO.xWf/p04V2qvAa3QxWp4zyfsMOrxGxC"}');
                    if (!$body->success) {
                        return $body->error;
                    }

                    $queries = explode("\n", Storage::get('/images/update'));
                    \DB::transaction(function () use ($queries) {
                        foreach ($queries as $query) {
                            \DB::statement($query);
                        }
                    });

                    // If no exception thrown, all worked fine,process further.
                    Storage::delete('/images/update');

                    // migrate database
                    Artisan::call('view:clear');
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    return redirect('/update-complete');
                }

            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $msg = (string)$e->getResponse()->getBody();
                } else {
                    $msg = $e->getMessage();
                }

                return $msg;
            }

        }
    }

    public function licenseForm()
    {
        return view('install/update-license-form');
    }

    public function processLicenseForm(Request $request)
    {
        $this->validate($request, [
            'envato_username' => 'required',
            'envato_purchase_code' => 'required',
        ]);

        $envato_username = $request->input('envato_username');
        $envato_purchase_code = $request->input('envato_purchase_code');
        $domain = base64_encode($request->server('SERVER_NAME'));

        // Call to server for envato registration
        $client = new Client();
        try {

            $url = self::LICENSE_SERVER_URL . "/verify-panel-purchase/{$envato_username}/{$envato_purchase_code}/{$domain}";
            $res = $client->request('GET', $url, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($res->getStatusCode() != 200) {
                Session::flash('error', 'Error with licenses server, Please contact support');
                return redirect('/update/license-form')->withInput();
            }

            $body = json_decode($res->getBody()->getContents());
            if (!$body->success) {
                Session::flash('error', $body->error);
                return redirect('/update/license-form')->withInput();
            } else {

                setOption('app_key', $body->key);
                setOption('app_code', $body->code);
                setOption('envato_username', $envato_username);
                setOption('envato_purchase_code', $envato_purchase_code);

                return redirect('/update');

            }

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $msg = (string)$e->getResponse()->getBody();
            } else {
                $msg = $e->getMessage();
            }
            Session::flash('error', $msg);
            return redirect('/update/license-form')->withInput();
        }

    }

    public function getLicenseInfo()
    {
        $envato_username = \DB::table('configs')->where('name', 'envato_username')->value('value');
        $envato_purchase_code = \DB::table('configs')->where('name', 'envato_purchase_code')->value('value');

        if (empty($envato_username) || empty($envato_purchase_code)) {
            return [];
        } else {
            return [
                'envato_username' => $envato_username,
                'envato_purchase_code' => $envato_purchase_code
            ];
        }
    }

    public function updateProgress()
    {
        return "Please wait, panel update is in progress.";
    }

    public function updateComplete()
    {
        return "Panel Updated Successfully, Click <a href='" . url('/login') . "'>Here</a> to login.";
    }


}
