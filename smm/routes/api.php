<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/order','OrderController@APIStoreOrder');
    Route::get('/status','OrderController@APIGetOrderStatus');
    // API v2
    Route::post('/v2','ApiController@index');
});

