<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class VerifyAppInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('database.transfer_mode') == "1") {
            if (password_verify($request->server('SERVER_NAME'), getOption('app_key', true))
                && password_verify(strrev($request->server('SERVER_NAME')), getOption('app_code', true))) {
                return redirect('/transfer/restore');
            }
            return redirect('/transfer/ready');
        }

        if (config('database.installed') == '%installed%') {
            return redirect('/install');
        }

        if (Storage::exists('images/update')) {
            return redirect('/update-progress');
        }


        \App::setLocale(request()->session()->get('locale', getOption('language')));
        return $next($request);
    }
}
