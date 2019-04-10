<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyIsUser
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
        //if user is admin then redirect to admin section
        if(Auth::user()->role == 'ADMIN'){
            return redirect('/admin');
        }
        config(['app.timezone' => Auth::user()->timezone]);
        return $next($request);
    }
}
