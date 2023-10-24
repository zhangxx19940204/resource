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
    $router->resource('user-graydzs', UserGraydzController::class);//用户的成绩结果
    $router->get('/graydzs_echarts', 'UserGraydzController@get_echarts');//用户的成绩的绘图页面

    $router->get('/show_form_data', 'HomeController@show_form_data');//展示用户提交的表单信息列表
    $router->get('/get_form_data', 'HomeController@get_form_data');//获取用户提交的表单信息列表
    $router->get('/print_form_data/{id}', 'HomeController@print_form_data');//打印用户提交的表单信息

    //资源统计列表页
    $router->resource('res-configs', Res\ResConfigController::class);
    $router->resource('res-datas', Res\ResDataController::class);
    $router->resource('res-distribution-configs', Res\ResDistributionConfigController::class);
    $router->resource('record_leave_robot_datas', Res\RecordLeaveRobotDataController::class);

    //邮件留言系统
    $router->resource('email-configs', Email\EmailConfigController::class);
    $router->resource('email-datas', Email\EmailDataController::class);
    $router->resource('email-passes', Email\EmailPassController::class);

    $router->resource('ec-users', Res\EcUserController::class);
    $router->resource('short-feedback_relatives', Res\ShortFeedbackController::class);
    $router->resource('distribute-logs', Res\DistributeLogController::class);
    $router->resource('mail-froms', Res\MailFromController::class);
    $router->resource('mail-belongs', Res\MailBelongController::class);

    //钉钉反馈系统
    $router->resource('feedback', DingTalk\FeedbackController::class);
    $router->resource('visits', DingTalk\VisitController::class);
    $router->resource('ding-talk-users', DingTalk\DingTalkUserController::class);
    $router->resource('dingtalk-ec-relatives', DingTalk\DingtalkEcRelativeController::class);
    $router->resource('ding-talk-projects', DingTalk\DingTalkProjectController::class);
    $router->resource('manage-relatives', DingTalk\ManageRelativeController::class);

    //53kf系统对接
    $router->resource('customerService_configs', CustomerService\ConfigController::class);
    $router->resource('customerService_records', CustomerService\RecordController::class);

    $router->resource('check-records', DingTalk\CheckRecordController::class);
    $router->resource('manage-check-records', DingTalk\ManageCheckRecordController::class);


    //微信小程序后台
    $router->resource('wechat-search-images', Wechat\SearchImageController::class);


});
