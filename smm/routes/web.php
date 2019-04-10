<?php
Route::group(['middleware' => 'VerifyAppIsNotInstalled'], function () {
    Route::get('/install', 'InstallationController@index');
    Route::get('/install/step1', 'InstallationController@step1');
    Route::post('/install/step1', 'InstallationController@storeStep1');
    Route::get('/install/success', 'InstallationController@success');
});


// Update logic middleware
Route::group(['middleware' => 'VerifyUpdateNeeded'], function () {
    Route::get('/update','UpdateController@update');
    Route::get('/update-progress','UpdateController@updateProgress');
    Route::get('/update/license-form','UpdateController@licenseForm');
    Route::post('/update/license-form','UpdateController@processLicenseForm');
});
Route::get('/update-complete','UpdateController@updateComplete');

Route::group(['middleware' => 'VerifyAppInstalled'], function () {

    Route::post('/change-lang', 'HomeController@changeLanguage');

    Auth::routes();
    Route::group(['middleware' => 'notAdmin'], function () {
        Route::get('/', 'HomeController@index');
        Route::get('/services', 'HomeController@showServices');
        Route::group(['middleware' => 'VerifyModuleAPIEnabled'], function () {
            Route::get('/api', 'HomeController@ApiDocV2');
            Route::get('/api-v1', 'HomeController@ApiDocV1');
        });
    });

    Route::get('page/{slug}', 'HomeController@showPage');

    // IPN Handlers
    Route::post('/payment/add-funds/bitcoin/bit-ipn', 'CoinPaymentsController@ipn');
    Route::post('/payment/add-funds/payza/status', 'PayzaController@ipn');
    Route::post('/payment/add-funds/paypal/status', 'PaypalController@ipn');
    Route::post('/payment/add-funds/instamojo/webhook', 'InstamojoController@webhook');
    Route::post('/payment/add-funds/skrill/ipn', 'SkrillController@ipn');
    Route::post('/payment/add-funds/paywant/status', 'PaywantController@paywantNotify');

    Route::group(['middleware' => 'auth'], function () {

        Route::group(['middleware' => 'user'], function () {

            Route::get('/order/new', 'OrderController@newOrder');
            Route::get('/service/get-packages/{service_id}', 'OrderController@getPackages');

            Route::post('/order', 'OrderController@store');
            Route::get('/order/mass-order', 'OrderController@showMassOrderForm');
            Route::post('/order/mass-order', 'OrderController@storeMassOrder');

            Route::get('/dashboard', 'DashboardController@index');
            Route::get('/orders', 'OrderController@index');
            Route::get('/orders-index/data', 'OrderController@indexData');
            Route::get('/orders-filter/{status}', 'OrderController@indexFilter');
            Route::get('/orders-filter-ajax/{status}/data', 'OrderController@indexFilterData');

            Route::get('/subscriptions', 'SubscriptionController@index');
            Route::get('/subscriptions/{id}', 'SubscriptionController@show');
            Route::get('/subscriptions-index/data', 'SubscriptionController@indexData');
            Route::get('/subscription/new', 'SubscriptionController@create');
            Route::post('/subscription', 'SubscriptionController@store');

            Route::get('/account/settings', 'AccountController@showSettings');
            Route::put('/account/password', 'AccountController@updatePassword');
            Route::put('/account/config', 'AccountController@updateConfig');
            Route::post('/account/api', 'AccountController@generateKey');
            Route::get('/account/funds-load-history', 'AccountController@getFundsLoadHistory');
            Route::get('account/funds-load-history-index/data', 'AccountController@getFundsLoadHistoryData');

            Route::get('/payment/add-funds', 'PaymentController@getPaymentMethods');
            Route::get('/payment/add-funds/stripe', 'StripeController@showForm');
            Route::post('/payment/add-funds/stripe', 'StripeController@store');

            Route::get('/payment/add-funds/paypal', 'PaypalController@showForm');
            Route::post('/payment/add-funds/paypal', 'PaypalController@store');
            Route::get('/payment/add-funds/paypal/success', 'PaypalController@success');
            Route::get('/payment/add-funds/paypal/cancel', 'PaypalController@cancel');

            Route::get('/payment/add-funds/bitcoin', 'CoinPaymentsController@showForm');
            Route::post('/payment/add-funds/bitcoin', 'CoinPaymentsController@store');
            Route::get('/payment/add-funds/bitcoin/cancel', 'CoinPaymentsController@cancel');
            Route::get('/payment/add-funds/bitcoin/success', 'CoinPaymentsController@success');

            Route::get('/payment/add-funds/payza', 'PayzaController@show');
            Route::post('/payment/add-funds/payza', 'PayzaController@store');
            Route::get('/payment/add-funds/payza/cancel', 'PayzaController@cancel');
            Route::get('/payment/add-funds/payza/success', 'PayzaController@success');

            Route::get('/payment/add-funds/bank-other', 'HomeController@showManualPaymentForm');

            Route::get('/payment/add-funds/instamojo', 'InstamojoController@show');
            Route::post('/payment/add-funds/instamojo', 'InstamojoController@store');
            Route::get('/payment/add-funds/instamojo/return', 'InstamojoController@redirectReturn');

            Route::get('/payment/add-funds/skrill', 'SkrillController@show');
            Route::post('/payment/add-funds/skrill', 'SkrillController@store');
            Route::get('/payment/add-funds/skrill/success', 'SkrillController@success');
            Route::get('/payment/add-funds/skrill/cancel', 'SkrillController@cancel');

            Route::get('/payment/add-funds/paytm', 'PaytmController@show');
            Route::post('/payment/add-funds/paytm', 'PaytmController@store');

            Route::get('/payment/add-funds/paywant', 'PaywantController@show');
            Route::post('/payment/add-funds/paywant', 'PaywantController@store');
            Route::get('/payment/add-funds/paywant/success', 'PaywantController@success');
            Route::get('/payment/add-funds/paywant/cancel', 'PaywantController@cancel');

            Route::get('/support', 'SupportController@index');
            Route::get('/support-index/data', 'SupportController@indexData');
            Route::get('/support/ticket/create', 'SupportController@create');
            Route::post('/support/ticket/store', 'SupportController@store');
            Route::get('/support/ticket/{id}', 'SupportController@show');
            Route::post('/support/{id}/message', 'SupportController@message');
        });

        // Admin
        Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

            Route::get('/', 'DashboardController@index');
            Route::post('/note', 'DashboardController@saveNote');
            Route::get('/account/settings', 'AccountController@showSettings');
            Route::put('/account/password', 'AccountController@updatePassword');

            Route::get('/system/settings', 'ConfigController@edit');
            Route::put('/system/settings', 'ConfigController@update');

            Route::resource('/payment-methods', 'PaymentMethodController');

            Route::resource('/services', 'ServiceController');
            Route::get('/services-index/data', 'ServiceController@indexData');

            Route::resource('/packages', 'PackageController');
            Route::get('/packages-index/data', 'PackageController@indexData');

            Route::post('/users/package-special-prices/{id}', 'UserController@packageSpecialPrices');
            Route::resource('/users', 'UserController');
            Route::post('/users/add-funds/{id}', 'UserController@addFunds');
            Route::get('/users-ajax/data', 'UserController@indexData');

            Route::resource('/orders', 'OrderController');
            Route::post('/order/{id}/complete', 'OrderController@completeOrder');
            Route::get('/orders-ajax/data', 'OrderController@indexData');
            Route::post('/orders-bulk-update', 'OrderController@bulkUpdate');
            Route::get('/orders-filter/{status}', 'OrderController@indexFilter');
            Route::get('/orders-filter-ajax/{status}/data', 'OrderController@indexFilterData');

            Route::get('/subscriptions', 'SubscriptionController@index');
            Route::get('/subscriptions-index/data', 'SubscriptionController@indexData');
            Route::get('/subscriptions/{id}/edit', 'SubscriptionController@edit');
            Route::post('/subscriptions/{id}', 'SubscriptionController@update');
            Route::put('/subscriptions/{id}/cancel', 'SubscriptionController@cancel');
            Route::put('/subscriptions/{id}/stop', 'SubscriptionController@stop');
            Route::get('/subscriptions/{id}/orders', 'SubscriptionController@orders');
            Route::post('/subscriptions/{id}/order', 'SubscriptionController@storeOrder');
            Route::get('/subscriptions-filter/{status}', 'SubscriptionController@indexFilter');
            Route::get('/subscriptions-filter-ajax/{status}/data', 'SubscriptionController@indexFilterData');

            Route::resource('/support/tickets', 'SupportController');
            Route::post('/support/{id}/message', 'SupportController@message');
            Route::get('/orders-index/data', 'SupportController@indexData');

            Route::get('/funds-load-history', 'UserController@getFundsLoadHistory');
            Route::get('/funds-load-history/data', 'UserController@getFundsLoadHistoryData');

            Route::get('/pages', 'PageController@index');
            Route::get('/page-edit/{slug}', 'PageController@edit');
            Route::put('/page-edit/{id}', 'PageController@update');

            Route::get('/automate/api-list', 'AutomateController@listApi');
            Route::get('/automate/send-orders', 'AutomateController@sendOrdersIndex');
            Route::get('/automate/send-orders-index/data', 'AutomateController@sendOrdersIndexData');
            Route::post('/automate/send-order-to-api', 'AutomateController@sendOrderToApi');

            Route::get('/automate/response-logs', 'AutomateController@getResponseLogsIndex');
            Route::get('/automate/response-logs-index/data', 'AutomateController@getResponseLogsIndexData');

            Route::get('/automate/api/add', 'AutomateController@addApi');
            Route::post('/automate/api/add', 'AutomateController@storeApi');
            Route::get('/automate/api/{id}/edit', 'AutomateController@editApi');
            Route::delete('/automate/api/{id}', 'AutomateController@deleteApi');
            Route::put('/automate/api/{id}', 'AutomateController@updateApi');
            Route::post('/automate/api/mapping/{id}', 'AutomateController@storeMapping');

            Route::get('/automate/get-status', 'AutomateController@getOrderStatusIndex');
            Route::get('/automate/get-status-index/data', 'AutomateController@getOrderStatusIndexData');
            Route::post('/automate/get-status-from-api', 'AutomateController@getOrderStatusFromAPI');
            Route::post('/automate/change-reseller', 'AutomateController@changeReseller');

            Route::get('/system/refresh', 'DashboardController@refreshSystem');
        });

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/admin/system/transfer', 'InstallationController@transfer');
            Route::post('/admin/system/transfer/process', 'InstallationController@processTransfer');
            Route::get('/admin/system/transfer/success', 'InstallationController@transferSuccess');
        });

    });

});

Route::get('/transfer/ready', 'InstallationController@transferReady');
Route::get('/transfer/restore', 'InstallationController@restore');
Route::post('/transfer/restore/process', 'InstallationController@processRestore');
Route::get('/transfer/restore/success', 'InstallationController@restoreSuccess');