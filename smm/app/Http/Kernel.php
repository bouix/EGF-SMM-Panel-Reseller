<?php

namespace App\Http;

use App\Http\Middleware\VerifyAppInstalled;
use App\Http\Middleware\VerifyAppIsNotInstalled;
use App\Http\Middleware\VerifyIsAdmin;
use App\Http\Middleware\VerifyIsNotAdmin;
use App\Http\Middleware\VerifyIsUser;
use App\Http\Middleware\VerifyModuleAPIEnabled;
use App\Http\Middleware\VerifyModuleSubscriptionEnabled;
use App\Http\Middleware\VerifyModuleSupportEnabled;
use App\Http\Middleware\VerifyUpdateNeeded;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'admin' => VerifyIsAdmin::class,
        'user' => VerifyIsUser::class,
        'notAdmin' => VerifyIsNotAdmin::class,
        'VerifyAppIsNotInstalled' => VerifyAppIsNotInstalled::class,
        'VerifyAppInstalled' => VerifyAppInstalled::class,
        'VerifyModuleAPIEnabled' => VerifyModuleAPIEnabled::class,
        'VerifyModuleSupportEnabled' => VerifyModuleSupportEnabled::class,
        'VerifyModuleSubscriptionEnabled' => VerifyModuleSubscriptionEnabled::class,
        'VerifyUpdateNeeded' => VerifyUpdateNeeded::class,
    ];
}
