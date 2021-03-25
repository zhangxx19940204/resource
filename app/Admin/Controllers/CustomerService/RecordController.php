<?php

namespace App\Admin\Controllers\CustomerService;

use App\Models\CustomerService\Record;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class RecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Record';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Record());
        Admin::js('/assets/admin/customer_service/mSlider.js');
        Admin::js('/assets/admin/customer_service/jquery.base64.js');
        Admin::js('/assets/admin/customer_service/mslider_config.js');
        //侧边栏的和聊天框的代码和引入
        Admin::css('https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css');
        Admin::css('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
        Admin::css('/assets/admin/customer_service/style.css');
        Admin::js('/assets/admin/customer_service/list.min.js');
        Admin::js('/assets/admin/customer_service/handlebars.min.js');
        Admin::js('/assets/admin/customer_service/script.js');
        $grid->column('id', __('编号'));
        $grid->column('config_id', __('账号信息'));
        $grid->column('data_guest_id', __('Data guest id'))->display(function ($data_guest_id){
            $customer_arr = json_decode($this,true);
            return '<button type="button" class="btn btn-secondary" data-whole_data="'.base64_encode(json_encode($customer_arr)).'">详细信息</button>';
        });
//        $grid->column('data_session', __('Data session'));
//        $grid->column('data_end', __('Data end'));
//        $grid->column('data_message', __('Data message'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('上次更新时间'));
        Admin::style('.mSlider-inner {overflow:auto;}');
        Admin::html('<div class="wrap" id="slider_message_div" style="margin-top: 0px;padding: 5px 0 0 10px;">留言内容加载中</div>');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Record::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('config_id', __('Config id'));
        $show->field('data_guest_id', __('Data guest id'));
        $show->field('data_session', __('Data session'));
        $show->field('data_end', __('Data end'));
        $show->field('data_message', __('Data message'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Record());

        $form->number('config_id', __('Config id'));
        $form->text('data_guest_id', __('Data guest id'));
        $form->text('data_session', __('Data session'));
        $form->text('data_end', __('Data end'));
        $form->text('data_message', __('Data message'));

        return $form;
    }
}
