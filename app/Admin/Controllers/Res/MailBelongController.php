<?php

namespace App\Admin\Controllers\Res;

use App\Models\MailBelong;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class MailBelongController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '邮件所属';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MailBelong());

        $user_obj = Auth::guard('admin')->user();

        $grid->header(function ($query) {
            $user_obj = Auth::guard('admin')->user();

            return '用户ID：'.$user_obj->id;
        });
        $grid->column('id', __('编号'));
        $grid->column('keyword', __('关键词'));
        $grid->column('belong', __('所属'));
        $grid->column('user_id', __('用户ID'));

        $grid->model()->orderBy('id', 'desc');
        if ($user_obj->id == 1) {
            // 不加 用户id的限制
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
        $show = new Show(MailBelong::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('keyword', __('Keyword'));
        $show->field('belong', __('Belong'));
        $show->field('user_id', __('User id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MailBelong());

        $form->text('keyword', __('匹配的关键词'));
        $form->text('belong', __('所属'));
        $form->number('user_id', __('用户id'));

        return $form;
    }
}
