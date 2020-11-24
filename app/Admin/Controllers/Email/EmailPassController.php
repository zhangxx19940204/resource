<?php

namespace App\Admin\Controllers\Email;

use App\Models\EmailPass;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class EmailPassController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '邮箱通过列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmailPass());

        $grid->column('id', __('Id'));
        $grid->column('email_account', __('Email account'));
        $grid->column('user_id', __('User id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $user_obj = Auth::guard('admin')->user();
        if ($user_obj->id == 1) {
            // code...

        } else {
            $grid->model()->whereIn('user_id', [$user_obj->id]);
        }

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
        $show = new Show(EmailPass::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('email_account', __('Email account'));
        $show->field('user_id', __('User id'));
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
        $user_obj = Auth::guard('admin')->user();

        $form = new Form(new EmailPass());
        $form->text('email_account', __('Email account'));
        $form->number('user_id', __('User id'))->default($user_obj->id);

        return $form;
    }
}
