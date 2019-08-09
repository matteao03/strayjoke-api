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

//web端路由
$api->version('v1', [
    'namespace' =>'App\Http\Controllers\Web'
], function($api){
    $api->post('/signupCode', 'VerifyCodeController@storeSignupCode');
    $api->post('/loginCode', 'VerifyCodeController@storeLoginCode');
    $api->post('/signup', 'AuthController@signup');
    $api->post('/loginByPassword', 'AuthController@loginByPassword');
    $api->post('/loginByCode', 'AuthController@loginByCode');
});

//律师端路由
$api->version('v1', [
    'namespace' =>'App\Http\Controllers\Lawyer',
    'prefix' => 'lawyer',
    'middleware' => 'api'
], function($api){
    //注册相关
    $api->post('/signupCode', 'LawyerController@storeSignupCode');
    $api->post('/signup', 'LawyerController@signup');
    //登录相关
    $api->post('/loginCode', 'LawyerController@storeLoginCode');
    $api->post('/loginByPassword', 'LawyerController@loginByPassword');
    $api->post('/loginByCode', 'LawyerController@loginByCode');
    
    $api->group(['middleware' => 'auth:lawyer'], function ($api) {
        $api->post('/logout', 'LawyerController@logout');
        
        $api->get('/info', 'LawyerController@getInfo');
        $api->patch('/info/{phone}', 'LawyerController@updateInfo');
        $api->post('/avatar', 'LawyerController@postAvatar');
       
        $api->post('/product', 'ProductController@store');
        $api->get('/products', 'ProductController@index');
        
        $api->get('/skus', 'ProductSkuController@index');
        $api->post('/sku', 'ProductSkuController@store');
        $api->patch('/sku/{sku}', 'ProductSkuController@update');
        $api->delete('/sku/{sku}', 'ProductSkuController@delete');
    });

    $api->get('/allProvinces', 'AreaController@getAllProvinces');
    $api->get('/cities', 'AreaController@getCitiesByProvinceId');
    $api->get('/areas', 'AreaController@getAreasByCityId');
});

//管理端路由
$api->version('v1', [
    'namespace' =>'App\Http\Controllers\Admin',
    'prefix' => 'admin'
], function($api){
    //登录相关
    $api->post('/login', 'AuthController@login');

    $api->group(['middleware' => 'auth:admin'], function ($api) {
        $api->get('/info', 'AuthController@getInfo');
        $api->get('/lawyers', 'LawyerController@index');
        $api->post('/lawyer/check', 'LawyerController@check');
        $api->get('/lawyerChecks', 'LawyerController@checkIndex');
    });
});