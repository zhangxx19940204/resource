<?php

namespace App\Admin\Controllers\Res;

use App\Models\shortFeedback_relative;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid = new Grid(new shortFeedback_relative());

        $grid->column('id', __('编号'));
        $grid->column('find_keywords_list', __('匹配词列表'));
        $grid->column('short_feeback', __('对应的简短反馈'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

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
        $show = new Show(shortFeedback_relative::findOrFail($id));

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
        $form = new Form(new shortFeedback_relative());

        $form->list('find_keywords_list', __('匹配词列表'));
        $form->text('short_feeback', __('简短反馈词'));

        return $form;
    }
}
