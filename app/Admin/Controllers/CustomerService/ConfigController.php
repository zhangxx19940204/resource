<?php

namespace App\Admin\Controllers\CustomerService;

use App\Models\CustomerService\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '53客服系统配置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config());

        $grid->column('id', __('编号'));
        $grid->column('custom_name', __('渠道名称'));
        $grid->column('account', __('客服登录账号'));
        $grid->column('token', __('53客服后台token值'));
        $grid->column('status', __('是否启用状态'))->bool(['1' => true, '0' => false]);
        $grid->column('is_syn', __('是否同步至分配'))->bool(['1' => true, '0' => false]);
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        $grid->model()->orderBy('id', 'desc');
        // 全部关闭
        $grid->disableFilter();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            // $actions->disableEdit();
            // $actions->disableDelete();
        });

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
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('custom_name', __('Custom name'));
        $show->field('token', __('Token'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Config());

        $form->text('custom_name', __('渠道名称'));
        $form->text('account', __('客服登录账号'));
        $form->text('token', __('53客服后台token值'));
        $form->switch('status', __('是否启用状态'));
        $form->switch('is_syn', __('是否同步至分配'));

        return $form;
    }
}
