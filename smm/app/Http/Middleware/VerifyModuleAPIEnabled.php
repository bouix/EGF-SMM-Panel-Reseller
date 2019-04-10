<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyModuleAPIEnabled
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
        if (Auth::check()) {
            if ((getOption('module_api_enabled') == 0)) {
                abort(403);
            }
        } else {
            $row = \DB::table('configs')->select('value')->where('name', 'module_api_enabled')->first();
            if ($row->value == 0) {
                abort(403);
            }
        }
        return $next($request);
    }
}
