<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyModuleSubscriptionEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if ((getOption('module_subscription_enabled') == 0)) {
                abort(403);
            }
        } else {
            $row = getOption('module_subscription_enabled',true);
            if ($row->value == 0) {
                abort(403);
            }
        }
        return $next($request);
    }
}
