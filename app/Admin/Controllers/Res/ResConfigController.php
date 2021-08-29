<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResConfig;
use Encore\Admin\Actions\Action;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '资源账户配置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResConfig());
        $user_obj = Auth::guard('admin')->user();
        $grid->column('id', __('编号'));
        $grid->column('user_id', __('用户id'));
        $grid->column('custom_name', __('自定义名称'));
        // $grid->column('account_id', __('账号相关id'));
        $grid->column('account', __('账号'));
        // $grid->column('account_password', __('账号密码'));
        // $grid->column('host_port', __('主机与端口'));
        $grid->column('status', __('状态'))->switch();
        $grid->column('belong', __('所属'));
        $grid->column('type', __('类型'));
        $grid->column('remarks', __('备注'));
        $grid->column('created_at', __('创建时间'))->display(function ($created_at){
            if(empty($created_at)){
                return '';
            }else{
                return date('Y-m-d H:i:s',strtotime($created_at));
            }

        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at){
            if(empty($updated_at)){
                return '';
            }else{
                return date('Y-m-d H:i:s',strtotime($updated_at));
            }
        });
        $grid->column('last_para', __('额外参数'));
        $grid->model()->orderBy('id', 'desc');
        if ($user_obj->id == 1) {
            // 不加 用户id的限制
        } else {
            $grid->model()->whereIn('user_id', [$user_obj->id]);
        }
        // $grid->disableCreateButton();
        $grid->actions(function ($actions){
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            // $actions->disableEdit();
            // 去掉查看
            $actions->disableView();

        });
        //查询过滤器
        $grid->expandFilter();
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(0.5, function ($filter) {
                $filter->like('custom_name', '自定义名称查询');
                $filter->like('remarks', '备注查询');
                $filter->in('status','状态')->checkbox([
                    '1'    => '启用',
                    '0'    => '未启用',
                ]);
            });
            $filter->column(0.5, function ($filter) {
                $project_list = DB::table('dingding_project')->get();
                $project_arr = [];
                foreach ($project_list as $single_project) {
                    $project_arr[$single_project->project_name] = $single_project->project_name;
                }
                $filter->in('belong', '所属')->multipleSelect($project_arr);

                $type_list = DB::table('res_config')
                    ->orderBy('status','desc')
                    ->get();
                $type_arr = [];
                foreach ($type_list as $single_type) {
                    $type_arr[$single_type->type] = $single_type->type;
                }
                $filter->in('type', '类型')->multipleSelect($type_arr);
            });
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
        $show = new Show(ResConfig::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('custom_name', __('Custom name'));
        $show->field('account_id', __('Account id'));
        $show->field('account', __('Account'));
        $show->field('account_password', __('Account password'));
        $show->field('host_port', __('Host port'));
        $show->field('status', __('Status'));
        $show->field('belong', __('Belong'));
        $show->field('type', __('Type'));
        $show->field('remarks', __('Remarks'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('last_para', __('Last para'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ResConfig());
        $user_obj = Auth::guard('admin')->user();
        $project_list = DB::table('dingding_project')->where('status','1')->get();
        $project_arr = [];
        foreach ($project_list as $single_project) {
            $project_arr[$single_project->project_name] = $single_project->project_name;
        }

        $form->radio('type', '选择账号类型')
            ->options([
                '微信广告'=> '微信广告',
                '百度53'=> '百度53',
                '知乎信息流'=> '知乎信息流',
                '全球'=> '全球',
                '寻餐网'=> '寻餐网',
                '微视频'=> '微视频',
                '5988'=> '5988',
                'mail'=> 'mail',
                '23网'=> '23网',
                '快马'=> '快马',
                '头条'=> '头条',
            ])->when('微信广告', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('百度53', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('知乎信息流', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('全球', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('寻餐网', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('微视频', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('5988', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('mail', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('23网', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('快马', function (Form $form) use ($user_obj) {
                $form->html('暂不开放支持','');
            })->when('头条', function (Form $form) use ($user_obj,$project_arr)  {
                $form->text('user_id', __('用户id(默认)'))->default($user_obj->id)->readonly();
                $form->text('custom_name', __('自定义名称'));
                $form->text('account_id', __('账号相关id'));
                $form->text('account', __('账号'));
                $form->hidden('account_password', __('账号密码'))->default('11111');
                // $form->text('host_port', __('主机与端口'));
                $form->switch('status', __('状态'));
                // $form->text('belong', __('所属'));
                $form->select('belong',__('所属'))->options($project_arr)->rules('required');
                // $form->text('type', __('类型'));
                $form->text('remarks', __('备注'));
                // $form->text('last_para', __('额外参数'));
            });

        $form->ignore(['nationality',]);
        return $form;
    }
}
