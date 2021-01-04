<?php

namespace App\Admin\Controllers\Res;

use App\Models\MailFrom;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class MailFromController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '邮件来源';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MailFrom());

        $user_obj = Auth::guard('admin')->user();

        $grid->header(function ($query) {
            $user_obj = Auth::guard('admin')->user();

            return '用户ID：'.$user_obj->id;
        });

        $grid->column('id', __('编号'));
        $grid->column('keyword', __('匹配关键词'));
        $grid->column('from', __('来源'));
        $grid->column('user_id', __('用户所属'))->display(function ($user_id){
            return $user_id;
        });

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
        $show = new Show(MailFrom::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('keyword', __('Keyword'));
        $show->field('from', __('From'));
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
        $form = new Form(new MailFrom());

        $form->text('keyword', __('匹配关键词'));
        $form->text('from', __('来源'));
        $form->number('user_id', __('用户ID'));

        return $form;
    }
}
