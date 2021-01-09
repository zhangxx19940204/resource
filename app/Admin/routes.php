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
    $router->any('/get_resource_all_by_day', 'HomeController@get_resource_all_by_day');

    //资源统计列表页
    $router->resource('res-datas', Res\ResDataController::class);
    $router->resource('res-distribution-configs', Res\ResDistributionConfigController::class);

    //邮件留言系统
    $router->resource('email-configs', Email\EmailConfigController::class);
    $router->resource('email-datas', Email\EmailDataController::class);
    $router->resource('email-passes', Email\EmailPassController::class);

    $router->resource('ec-users', Res\EcUserController::class);
    $router->resource('short-feedback_relatives', Res\ShortFeedbackController::class);
    $router->resource('distribute-logs', Res\DistributeLogController::class);
    $router->resource('mail-froms', Res\MailFromController::class);
    $router->resource('mail-belongs', Res\MailBelongController::class);


});
