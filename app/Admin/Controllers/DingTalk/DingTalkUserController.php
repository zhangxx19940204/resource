<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\DingTalkUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DingTalkUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DingTalkUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DingTalkUser());

        $grid->column('id', __('Id'));
        $grid->column('userid', __('Userid'));
        $grid->column('openid', __('Openid'));
        $grid->column('unionid', __('Unionid'));
        $grid->column('name', __('Name'));
        $grid->column('mobile', __('Mobile'));
        $grid->column('position', __('Position'));
        $grid->column('avatar', __('Avatar'));
        $grid->column('department_id', __('Department id'));
        $grid->column('department_name', __('Department name'));
        $grid->column('status', __('Status'));
        $grid->column('active', __('Active'));
        $grid->column('create_date', __('Create date'));

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
        $show = new Show(DingTalkUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('userid', __('Userid'));
        $show->field('openid', __('Openid'));
        $show->field('unionid', __('Unionid'));
        $show->field('name', __('Name'));
        $show->field('mobile', __('Mobile'));
        $show->field('position', __('Position'));
        $show->field('avatar', __('Avatar'));
        $show->field('department_id', __('Department id'));
        $show->field('department_name', __('Department name'));
        $show->field('status', __('Status'));
        $show->field('active', __('Active'));
        $show->field('create_date', __('Create date'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DingTalkUser());

        $form->text('userid', __('Userid'));
        $form->text('openid', __('Openid'));
        $form->text('unionid', __('Unionid'));
        $form->text('name', __('Name'));
        $form->mobile('mobile', __('Mobile'));
        $form->text('position', __('Position'));
        $form->image('avatar', __('Avatar'));
        $form->text('department_id', __('Department id'));
        $form->text('department_name', __('Department name'));
        $form->switch('status', __('Status'));
        $form->text('active', __('Active'))->default('1');
        $form->datetime('create_date', __('Create date'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
