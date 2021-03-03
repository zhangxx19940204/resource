<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\Visit;
use App\Models\DingTalk\DingTalkUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class VisitController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '客户来访表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Visit());

        $grid->column('id', __('ID'));
        $grid->column('dingding_user_id', __('用户名'))->display(function($dingding_user_id) {
            $user_info = DingTalkUser::find($dingding_user_id);
            return $user_info->department_name . $user_info->name;
        });
        $grid->column('blong', __('品牌所属'));
        // $grid->column('status', __('Status'));
        $grid->column('include_time', __('录入时间'));
        $grid->column('visit_month', __('月份'));
        $grid->column('visit_date', __('来访日期'));
        $grid->column('visit_brand', __('品牌'));
        $grid->column('visit_name', __('客户姓名'));
        $grid->column('visit_sex', __('性别'));
        $grid->column('visit_result', __('来访结果'));
        $grid->column('money_type', __('进款分类'));
        $grid->column('money_enter', __('入款'));
        $grid->column('pending_closing', __('待收尾款'));
        $grid->column('shop_type', __('店型'));
        $grid->column('invitee', __('邀约人'));
        $grid->column('negotiation_manager', __('谈判经理'));
        $grid->column('department', __('部门'));
        $grid->column('resource_platform', __('资源平台'));

        $grid->column('phone', __('电话'));
        $grid->column('address', __('地址'));
        $grid->column('age', __('年龄'));
        $grid->column('occupational', __('职业'));
        $grid->column('reason_not_signed', __('未签原因'));
        $grid->column('is_partner', __('是否有合伙人'));
        $grid->column('visit_cycle', __('来访周期（距离拿资源时间）'));
        $grid->column('signing_cycle', __('签约周期'));
        $grid->column('create_date', __('创建时间'));

        $grid->export(function ($export) {

            $export->filename('客户来访进款'.time().'.csv');

            $export->except(['create_date', 'id']);

            // $export->only([]);

            // $export->originalValue(['visit_month']);

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

                $project_list = DB::table('dingding_project')->get();
                $project_arr = [];
                foreach ($project_list as $single_project) {
                    $project_arr[$single_project->project_name] = $single_project->project_name;
                }
                $filter->in('blong', '品牌所属')->multipleSelect($project_arr);
                $filter->between('visit_date', '来访日期')->date();


            });
            $filter->column(1/2, function ($filter) {

                // 在这里添加字段过滤器
                $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
                $user_arr = [];
                foreach ($user_list as $k=>$v){
                    $user_arr[$v['id']] = $v['department_name'].$v['name'];
                }

                $filter->in('dingding_user_id','用户')->multipleSelect($user_arr);

                $filter->like('phone', '电话');
                $filter->like('visit_name', '客户姓名');

                $filter->between('create_date', '创建时间')->datetime();

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
        $grid->model()->orderBy('create_date', 'desc');
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
        $show = new Show(Visit::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('dingding_user_id', __('用户名'))->display(function($dingding_user_id) {
            $user_info = DingTalkUser::find($dingding_user_id);
            return $user_info->department_name . $user_info->name;
        });
        $show->field('blong', __('品牌所属'));
        // $show->field('status', __('Status'));
        $show->field('create_date', __('创建时间'));
        $show->field('visit_month', __('月份'));
        $show->field('visit_date', __('来访日期'));
        $show->field('visit_brand', __('品牌'));
        $show->field('visit_name', __('客户姓名'));
        $show->field('visit_sex', __('性别'));
        $show->field('visit_result', __('来访结果'));
        $show->field('money_type', __('进款分类'));
        $show->field('money_enter', __('入款'));
        $show->field('pending_closing', __('待收尾款'));
        $show->field('shop_type', __('店型'));
        $show->field('invitee', __('邀约人'));
        $show->field('negotiation_manager', __('谈判经理'));
        $show->field('department', __('部门'));
        $show->field('resource_platform', __('资源平台'));
        $show->field('include_time', __('录入时间'));
        $show->field('phone', __('电话'));
        $show->field('address', __('地址'));
        $show->field('age', __('年龄'));
        $show->field('occupational', __('职业'));
        $show->field('reason_not_signed', __('未签原因'));
        $show->field('is_partner', __('是否有合伙人'));
        $show->field('visit_cycle', __('来访周期（距离拿资源时间）'));
        $show->field('signing_cycle', __('签约周期'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Visit());

        $user_list = DingTalkUser::get(['id','name','department_name'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['department_name'].$v['name'];
        }
        $form->select('dingding_user_id',__('选择用户'))->options($user_arr);

        // $form->number('dingding_user_id',__('用户名'));
        $form->text('blong',__('品牌所属'));
        // $form->text('status',__('Status'));
        $form->datetime('create_date',__('创建时间'))->default(date('Y-m-d H:i:s'));
        $form->text('visit_month',__('月份'));
        $form->date('visit_date',__('来访日期'))->default(date('Y-m-d'));
        $form->text('visit_brand',__('品牌'));
        $form->text('visit_name',__('客户姓名'));
        $form->text('visit_sex',__('性别'));
        $form->text('visit_result',__('来访结果'));
        $form->text('money_type',__('进款分类'));
        $form->text('money_enter',__('入款'));
        $form->text('pending_closing',__('待收尾款'));
        $form->text('shop_type',__('店型'));
        $form->text('invitee',__('邀约人'));
        $form->text('negotiation_manager',__('谈判经理'));
        $form->text('department',__('部门'));
        $form->text('resource_platform',__('资源平台'));
        $form->datetime('include_time',__('录入时间'))->default(date('Y-m-d H:i:s'));
        $form->mobile('phone',__('电话'));
        $form->text('address',__('地址'));
        $form->text('age',__('年龄'));
        $form->text('occupational',__('职业'));
        $form->text('reason_not_signed',__('未签原因'));
        $form->text('is_partner',__('是否有合伙人'));
        $form->text('visit_cycle',__('来访周期（距离拿资源时间）'));
        $form->text('signing_cycle',__('签约周期'));

        return $form;
    }
}
