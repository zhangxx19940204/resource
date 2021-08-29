<?php

namespace App\Admin\Controllers\Res;

use App\Models\DingTalk\DingTalkUser;
use App\Models\record_leave_robot_data;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class RecordLeaveRobotDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '机器指令记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new record_leave_robot_data());
        $user_obj = Auth::guard('admin')->user();

        $grid->column('id', __('编号'));
        $grid->column('dingding_userid', __('钉钉用户编号'));
        $grid->column('key_word', __('用户发送的指令'));
        $grid->column('res_word', __('机器的回复'));
        $grid->column('created_at', __('创建时间'))->display(function ($created_at) {
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at) {
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });
        $grid->model()->orderBy('id', 'desc');
        // 全部关闭
        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $user_list = DingTalkUser::get(['id','name','department_name','userid'])->toarray();
            $user_arr = [];
            foreach ($user_list as $k=>$v){
                $user_arr[$v['userid']] = $v['department_name'].$v['name'].$v['userid'];
            }
            $filter->in('dingtalk_userid','钉钉用户')->multipleSelect($user_arr);
            $filter->like('key_word', '用户发送的指令');
            $filter->between('created_at', '创建时间')->datetime();
        });

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
        $show = new Show(record_leave_robot_data::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('dingding_userid', __('Dingding userid'));
        $show->field('key_word', __('Key word'));
        $show->field('res_word', __('Res word'));
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
        $form = new Form(new record_leave_robot_data());

        $form->text('dingding_userid', __('Dingding userid'));
        $form->text('key_word', __('Key word'));
        $form->text('res_word', __('Res word'));

        return $form;
    }
}
