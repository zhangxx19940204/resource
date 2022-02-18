<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\DingTalkUser;
use App\Models\DingTalk\ManageRelative;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ManageRelativeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '管理与员工';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ManageRelative());

        $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['department_name'].$v['name'];
        }
        $grid->column('id', __('编号'));
        $grid->column('manager_id', __('管理员'))->display(function ($manager_id) use($user_arr)  {
            return $user_arr[$manager_id];
        });
        $grid->column('member_id_list', __('员工列表'))->display(function ($member_id_list) use($user_arr)  {
            $member_str = '';
            foreach ($member_id_list as $member_id){
                $member_str .= $user_arr[$member_id].'<br>';
            }
            return $member_str;
        });
        $grid->column('status', __('状态'))->switch();
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        //查询过滤器
        $grid->expandFilter();
        $grid->filter(function($filter) use($user_arr) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(0.5, function ($filter) use($user_arr) {
                $filter->equal('manager_id','管理员')->select($user_arr);
            });
            $filter->column(1/2, function ($filter) {

            });

        });

        $grid->actions(function ($actions) {

            // 去掉删除
            // $actions->disableDelete();

            // 去掉编辑
            // $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
        });

        $grid->model()->orderBy('id', 'desc');

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
        $show = new Show(ManageRelative::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('manager_id', __('Manager id'));
        $show->field('member_id_list', __('Member id list'));
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
        $form = new Form(new ManageRelative());

        // $form->number('manager_id', __('管理员'));
        $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['department_name'].$v['name'];
        }
        $form->select('manager_id', __('管理员'))->options($user_arr);
        $form->multipleSelect('member_id_list', __('员工'))->options($user_arr);
        $states = [
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '关闭', 'color' => 'danger'],
        ];
        $form->switch('status', __('状态'))->states($states);

        return $form;
    }
}
