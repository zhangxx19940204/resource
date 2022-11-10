<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\DingTalkProject;
use App\Models\DingTalk\DingTalkUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class DingTalkProjectController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '钉钉/EC 项目配置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DingTalkProject());
        $user_obj = Auth::guard('admin')->user();

        $grid->column('id', __('编号'));
        $grid->column('project_name', __('项目名称'));
        $grid->column('status', __('是否启用'))->bool(['1' => true, '0' => false]);
        $grid->column('created_at', __('创建时间'))->display(function ($created_at) {
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at) {
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });

        $grid->model()->orderBy('id', 'desc');
        // 全部关闭
        $grid->disableFilter();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            // $actions->disableEdit();
            // $actions->disableDelete();
        });

        if ($user_obj->id == 1) {
            // 不加 用户id的限制
        } else {
            // $grid->model()->whereIn('user_id', [$user_obj->id]);
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
        $show = new Show(DingTalkProject::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('project_name', __('Project name'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'))->display(function ($created_at) {
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
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
        $form = new Form(new DingTalkProject());

        $form->text('project_name', __('项目名称'));
        $form->switch('status', __('是否启用'))->default(1);

        return $form;
    }
}
