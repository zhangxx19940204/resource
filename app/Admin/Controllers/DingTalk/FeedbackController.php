<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\Feedback;
use App\Models\DingTalk\DingTalkUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class FeedbackController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '资源反馈';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feedback());

        $grid->column('id', __('ID'));

        $grid->column('dingding_user_id', __('用户名'))->display(function($dingding_user_id) {
            $user_info = DingTalkUser::find($dingding_user_id);
            return $user_info->department_name . $user_info->name;
        });

        $grid->column('blong', __('品牌所属'));
        // $grid->column('status', __('Status'));
        $grid->column('updated_at', __('上次更新时间'))->display(function ($date) {
            return date('Y-m-d H:i:s',strtotime($date));
        });
        $grid->column('created_at', __('创建时间'))->display(function ($date) {
            return date('Y-m-d H:i:s',strtotime($date));
        });
        $grid->column('data_date', __('日期'));
        $grid->column('name', __('姓名'));
        $grid->column('customer_concerns', __('客户顾虑点'))->width(200);
        $grid->column('phone', __('手机号'));
        $grid->column('feedback_short', __('反馈'));
        $grid->column('feedback_detail', __('跟进记录'))->width(700);

        $grid->export(function ($export) {

            $export->filename('资源反馈'.time().'.csv');

            // $export->except(['column1', 'column2']);

            $export->only(['dingding_user_id', 'blong', 'data_date','name','customer_concerns', 'phone', 'feedback_short', 'feedback_detail']);

            // $export->originalValue(['column1', 'column2']);

            $export->column('dingding_user_id', function ($value, $original) {
                return $value;
            });
        });

        //查询过滤器
        $grid->expandFilter();
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(0.5, function ($filter) {

                $short_feedback_list = DB::table('short_feedback_relative')->get();
                $project_list = DB::table('dingding_project')->where('status','1')->get();
                $project_arr = [];
                foreach ($project_list as $single_project) {
                    $project_arr[$single_project->project_name] = $single_project->project_name;
                }
                $feedback_short_arr = [];
                foreach ($short_feedback_list as $short_feedback) {
                    $feedback_short_arr[$short_feedback->short_feeback] = $short_feedback->short_feeback;
                }
                $filter->in('blong', '品牌所属')->multipleSelect($project_arr);
                $filter->between('data_date', '日期')->date();
                $filter->in('feedback_short', '反馈')->multipleSelect($feedback_short_arr);
            });
            $filter->column(1/2, function ($filter) {

                // 在这里添加字段过滤器
                $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
                $user_arr = [];
                foreach ($user_list as $k=>$v){
                    $user_arr[$v['id']] = $v['department_name'].$v['name'];
                }
                $filter->in('dingding_user_id','用户')->multipleSelect($user_arr);
                $filter->like('phone', '手机号');
                $filter->like('name', '姓名');
                $filter->ilike('feedback_detail', '跟进记录');
                $filter->between('created_at', '创建日期')->datetime();
                $filter->between('updated_at', '上次更新日期')->datetime();
            });




        });

        $grid->actions(function ($actions) {

            // 去掉删除
            $actions->disableDelete();

            // 去掉编辑
            // $actions->disableEdit();

            // 去掉查看
            // $actions->disableView();
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
        $show = new Show(Feedback::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('dingding_user_id', __('用户名'));
        $show->field('blong', __('品牌所属'));
        // $show->field('status', __('Status'));
        $show->field('updated_at', __('上次更新时间'));
        $show->field('created_at', __('创建时间'));
        $show->field('data_date', __('日期'));
        $show->field('phone', __('手机号'));
        $show->field('feedback_short', __('反馈'));
        $show->field('feedback_detail', __('跟进记录'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Feedback());
        $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['department_name'].$v['name'];
        }
        $form->select('dingding_user_id',__('选择用户'))->options($user_arr);
        // $form->number('dingding_user_id', __('用户名'));
        $form->text('blong', __('品牌所属'));
        // $form->text('status', __('Status'));
        $form->date('data_date', __('日期'))->default(date('Y-m-d'));
        $form->mobile('phone', __('手机号'));
        $form->text('feedback_short', __('反馈'));
        $form->textarea('feedback_detail', __('跟进记录'));

        return $form;
    }
}
