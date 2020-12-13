<?php

namespace App\Admin\Controllers\Res;

use App\Models\EcUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EcUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'EcUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EcUser());

        $grid->column('id', __('Id'));
        $grid->column('deptId', __('DeptId'));
        $grid->column('status', __('Status'));
        $grid->column('title', __('Title'));
        $grid->column('userId', __('UserId'));
        $grid->column('userName', __('UserName'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(EcUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('deptId', __('DeptId'));
        $show->field('status', __('Status'));
        $show->field('title', __('Title'));
        $show->field('userId', __('UserId'));
        $show->field('userName', __('UserName'));
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
        $form = new Form(new EcUser());

        $form->number('deptId', __('DeptId'));
        $form->switch('status', __('Status'));
        $form->text('title', __('Title'));
        $form->number('userId', __('UserId'));
        $form->text('userName', __('UserName'));

        return $form;
    }
}
