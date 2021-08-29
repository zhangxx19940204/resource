<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\DingtalkEcRelative;
use App\Models\DingTalk\DingTalkUser;
use App\Models\EcUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class DingtalkEcRelativeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '钉钉EC关系表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DingtalkEcRelative());
        $ec_user_list = DB::table('ec_users')->get();
        $ec_user_arr = [];
        foreach ($ec_user_list as $k1=>$v1){
            $ec_user_arr[$v1->userId] = $v1;
        }
        $dingding_user_list = DB::table('dingding_user')->get();
        $dingding_user_arr = [];
        foreach ($dingding_user_list as $k2=>$v2){
            $dingding_user_arr[$v2->userid] = $v2;
        }

        $grid->column('id', __('编号'));
        $grid->column('dingtalk_userid', __('钉钉用户id'))->display(function ($dingtalk_userid) use($dingding_user_arr) {
            if (array_key_exists($dingtalk_userid,$dingding_user_arr)){
                $dingding_user = $dingding_user_arr[$dingtalk_userid];
                return $dingding_user->department_name.$dingding_user->position.'-'.$dingding_user->name;
            }else{
                return '暂未收录的钉钉用户：'.$dingtalk_userid;
            }
        });
        $grid->column('ec_userid', __('ec系统的用户id'))->display(function ($ec_userid) use($ec_user_arr) {
            if (array_key_exists($ec_userid,$ec_user_arr)){
                return $ec_user_arr[$ec_userid]->deptName;
            }else{
                return '暂未收录的EC用户：'.$ec_userid;
            }

        });
        $grid->column('created_at', __('创建时间'))->display(function ($created_at) {
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            // $actions->disableDelete();
        });
        $grid->disableCreateButton();

        //查询过滤器
        $grid->expandFilter();
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->column(0.5, function ($filter) {
                // 在这里添加字段过滤器
                $user_list = DingTalkUser::get(['id','name','department_name','userid'])->toarray();
                $user_arr = [];
                foreach ($user_list as $k=>$v){
                    $user_arr[$v['userid']] = $v['department_name'].$v['name'].$v['userid'];
                }
                $filter->in('dingtalk_userid','钉钉用户')->multipleSelect($user_arr);

                // 在这里添加字段过滤器
                $ecuser_list = EcUser::get(['id','deptName','userId'])->toarray();
                $ecuser_arr = [];
                foreach ($ecuser_list as $k1=>$v1){
                    $ecuser_arr[$v1['userId']] = $v1['deptName'].$v1['userId'];
                }
                $filter->in('ec_userid','EC用户的查询')->multipleSelect($ecuser_arr);
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
        $show = new Show(DingtalkEcRelative::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('dingTalk_userId', __('DingTalk userId'));
        $show->field('Ec_userId', __('Ec userId'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DingtalkEcRelative());

        $form->text('dingTalk_userId', __('DingTalk userId'));
        $form->text('Ec_userId', __('Ec userId'));

        return $form;
    }
}
