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
    'namespace' => 'App\Http\Controllers\Web',
    'middleware' => 'api'
], function ($api) {
    $api->post('/signupCode', 'VerifyCodeController@storeSignupCode');
    $api->post('/loginCode', 'VerifyCodeController@storeLoginCode');
    $api->post('/forgetCode', 'VerifyCodeController@storeForgetCode');
    $api->post('/verifyForgetCode', 'AuthController@verifyForgetCode');
    $api->patch('/password', 'AuthController@resetPassword');
    $api->post('/signup', 'AuthController@signup');
    $api->post('/loginByPassword', 'AuthController@loginByPassword');
    $api->post('/loginByCode', 'AuthController@loginByCode');

    $api->get('/products', 'ProductController@index');
    $api->get('/skus', 'ProductSkuController@index');
    $api->get('/cert/{sku}', 'OrderController@preStore');
    $api->get('/tags', 'TagController@index');
    $api->get('/tag/{tag}/lawyers', 'TagController@lawyers');
    $api->get('/lawyer/{lawyer}', 'LawyerController@detail');
    $api->get('/payment/{order}/alipay', ['as' => 'alipay', 'uses' => 'PaymentController@payByAlipay']);
    $api->get('/payment/{order}/wxpay', ['as' => 'wxpay', 'uses' => 'PaymentController@payByWechat']);
    $api->get('payment/alipay/return', ['as' => 'payment.alipay.return', 'uses' => 'PaymentController@alipayReturn']);
    $api->post('payment/alipay/notify', ['as' => 'payment.alipay.notify', 'uses' => 'PaymentController@alipayNotify']);
    $api->post('payment/wechat/notify', ['as' => 'payment.wechat.notify', 'uses' => 'PaymentController@wechatNotify']);

    $api->group(['middleware' => 'refreshToken:user'], function ($api) {
        $api->get('/info', 'AuthController@getInfo');
        $api->patch('auth/name', 'AuthController@updateName');
        $api->patch('auth/birth', 'AuthController@updateBrith');
        $api->patch('auth/avatar', 'AuthController@updateAvatar');
        $api->post('/order', 'OrderController@store');
        $api->get('/orders', 'OrderController@index');
        $api->get('/order/{order}', 'OrderController@detail');
        $api->post('/order/{order}/comment', 'OrderCommentController@store');
        $api->post('/lawyers/{lawyer}/collect', 'LawyerController@collect');
        $api->delete('/lawyers/{lawyer}/collect', 'LawyerController@uncollect');
        $api->get('/lawyer/{lawyer}/comments', 'OrderCommentController@index');
        $api->get('/collectLawyers', 'AuthController@indexLawyers');
        $api->get('/coupons', 'CouponController@index');
    });
});

//律师端路由
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Lawyer',
    'prefix' => 'lawyer',
    'middleware' => 'api'
], function ($api) {
    //注册相关
    $api->post('/signupCode', 'LawyerController@storeSignupCode');
    $api->post('/signup', 'LawyerController@signup');
    //登录相关
    $api->post('/loginCode', 'LawyerController@storeLoginCode');
    $api->post('/loginByPassword', 'LawyerController@loginByPassword');
    $api->post('/loginByCode', 'LawyerController@loginByCode');
    $api->post('/forgetCode', 'LawyerController@storeForgetCode');
    $api->post('/verifyForgetCode', 'LawyerController@verifyForgetCode');
    $api->patch('/password', 'LawyerController@resetPassword');
    $api->post('/logout', 'LawyerController@logout');

    $api->group(['middleware' => 'refreshToken:lawyer'], function ($api) {

        $api->get('/info', 'LawyerController@getInfo');
        $api->patch('/info/{phone}', 'LawyerController@updateInfo');
        $api->post('/avatar', 'LawyerController@postAvatar');
        $api->get('/lawyerChecks', 'LawyerController@checkIndex');

        $api->post('/product', 'ProductController@store');
        $api->get('/products', 'ProductController@index');
        $api->patch('/product/{product}', 'ProductController@update');

        $api->get('/skus', 'ProductSkuController@index');
        $api->post('/sku', 'ProductSkuController@store');
        $api->patch('/sku/{sku}', 'ProductSkuController@update');
        $api->delete('/sku/{sku}', 'ProductSkuController@delete');

        $api->get('/orders', 'OrderController@index');
    });

    $api->get('/allProvinces', 'AreaController@getAllProvinces');
    $api->get('/cities', 'AreaController@getCitiesByProvinceId');
    $api->get('/areas', 'AreaController@getAreasByCityId');
});


//管理端路由
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => 'admin'
], function ($api) {
    //登录相关
    $api->post('/login', 'AuthController@login');

    $api->group(['middleware' => 'refreshToken:admin'], function ($api) {
        $api->get('/info', 'AuthController@getInfo');
        $api->get('/users', 'UserController@index');
        $api->get('/lawyers', 'LawyerController@index');
        $api->post('/lawyer/check', 'LawyerController@check');
        $api->get('/lawyerChecks', 'LawyerController@checkIndex');
        $api->get('/products', 'ProductController@index');
        $api->get('/orders', 'OrderController@index');
        $api->get('/tags', 'TagController@index');
        $api->post('/tag', 'TagController@store');
        $api->patch('/tag/{tag}', 'TagController@update');
        $api->delete('/tag/{id}', 'TagController@delete');
        $api->get('/couponTemplates', 'CouponTemplateController@index');
        $api->post('/couponTemplate', 'CouponTemplateController@store');
        $api->patch('/couponTemplate/{template}', 'CouponTemplateController@update');
        $api->delete('/couponTemplate/{template}', 'CouponTemplateController@delete');
    });
});
