<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    //资源统计列表页
    $router->resource('res-datas', Res\ResDataController::class);

    //邮件留言系统
    $router->resource('email-configs', Email\EmailConfigController::class);
    $router->resource('email-datas', Email\EmailDataController::class);
    $router->resource('email-passes', Email\EmailPassController::class);


});
