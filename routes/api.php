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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' =>'App\Http\Controllers\Api'
], function($api){
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api){
        $api->post('signupCode', 'VerifyCodeController@storeSignupCode');
        $api->post('loginCode', 'VerifyCodeController@storeLoginCode');
        $api->post('signup', 'AuthController@signup');
        $api->post('loginByPassword', 'AuthController@loginByPassword');
        $api->post('loginByCode', 'AuthController@loginByCode');
    });
    
    $api->post('product', 'ProductController@store');
    $api->get('products', 'ProductController@index');
    $api->get('skus', 'ProductSkuController@index');
    $api->post('sku', 'ProductSkuController@store');

    $api->group([
        'middleware' => 'bindings',
    ], function($api){
        $api->patch('sku/{sku}', 'ProductSkuController@update');
        $api->delete('sku/{sku}', 'ProductSkuController@delete');
    });
});
