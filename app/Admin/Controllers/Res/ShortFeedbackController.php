<?php

namespace App\Admin\Controllers\Res;

use App\Models\shortFeedbackRelative;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class ShortFeedbackController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '简短反馈的匹配库';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new shortFeedbackRelative());
        $user_obj = Auth::guard('admin')->user();

        $grid->column('id', __('编号'));
        // $grid->column('find_keywords_list', __('匹配词列表'));
        $grid->column('short_feeback', __('对应的简短反馈'));
        $grid->column('created_at', __('创建时间'))->display(function ($created_at) {
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at) {
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });
        $grid->model()->orderBy('id', 'desc');
        if ($user_obj->id == 1) {
            // 不加 用户id的限制
        } else {
            $grid->model()->whereIn('user_id', [$user_obj->id]);
        }
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
        $show = new Show(shortFeedbackRelative::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('find_keywords_list', __('Find keywords list'));
        $show->field('short_feeback', __('Short feeback'));
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
        $form = new Form(new shortFeedbackRelative());

        // $form->list('find_keywords_list', __('匹配词列表'));
        $form->text('short_feeback', __('简短反馈词'));

        return $form;
    }
}
