<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResDistributionConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResDistributionConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '配置分配功能';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResDistributionConfig());

        $grid->column('id', __('Id'));
        $grid->column('belong', __('所属（与资源所属关联）'))->editable();
        $grid->column('recyclable_list', __('可重复使用列表'))->display(function ($recyclable_list){
            $recyclable_list_arr = DB::table('ec_users')->whereIn('userId', array_keys($recyclable_list))->get();
            $new_recyclable_list_arr = [];
            foreach ($recyclable_list_arr as $single_recyclable_list_arr){
                $single_recyclable_list_arr->sort = $recyclable_list[$single_recyclable_list_arr->userId];
                $new_recyclable_list_arr[] = $single_recyclable_list_arr;
            }

            $new_recyclable_arr = array_column($new_recyclable_list_arr, 'sort');
            array_multisort($new_recyclable_arr, SORT_DESC, $new_recyclable_list_arr);

            $recyclable_str = '';
            foreach ($new_recyclable_list_arr as $key=>$single_recyclable){
                $recyclable_str .= '<strong>'.$single_recyclable->userName.'</strong>:'.$recyclable_list[$single_recyclable->userId].'；<br/>';
            }
            return $recyclable_str;
        });
        $grid->column('active_list', __('正在使用的列表'))->display(function ($active_list){
            $active_list_arr = DB::table('ec_users')->whereIn('userId', array_keys($active_list))->get();

            $new_active_list_arr = [];
            foreach ($active_list_arr as $single_active_list_arr){
                $single_active_list_arr->sort = $active_list[$single_active_list_arr->userId];
                $new_active_list_arr[] = $single_active_list_arr;
            }

            $new_active_arr = array_column($new_active_list_arr, 'sort');
            array_multisort($new_active_arr, SORT_DESC, $new_active_list_arr);

            $active_str = '';
            foreach ($new_active_list_arr as $single_active){
                $active_str .= '<strong>'.$single_active->userName.'</strong>:'.$active_list[$single_active->userId].'；<br/>';
            }
            return $active_str;
        });
        $grid->column('except_list', __('排除列表'))->display(function ($except_list){
            $except_list_arr = DB::table('ec_users')->whereIn('userId', $except_list)->get();
            $except_str = '';
            foreach ($except_list_arr as $single_except){
                $except_str .= '<strong>'.$single_except->userName.'</strong>；<br/>';
            }
            return $except_str;
        });
        $grid->column('except_auto_account_list', __('排除账号自动分配列表'))->display(function ($except_auto_account_list){
            $except_auto_account_list_arr = DB::table('res_config')->whereIn('id', $except_auto_account_list)->get();
            $except_account_str = '';
            foreach ($except_auto_account_list_arr as $single_except_account){
                $except_account_str .= '<strong>'.$single_except_account->custom_name.'</strong>；<br/>';
            }
            return $except_account_str;
        });

        $grid->column('auto_distribute_list', __('自动分配列表'))->display(function ($auto_distribute_list){
            $auto_distribute_arr = json_decode($auto_distribute_list,true);
            $auto_distribute_values = is_array($auto_distribute_arr)? array_values($auto_distribute_arr): [];
            $auto_list_arr = DB::table('ec_users')->whereIn('userId',$auto_distribute_values)->get();

            $auto_str = '';
            foreach ($auto_list_arr as $single_auto){
                $auto_str .= '<strong>'.$single_auto->userName.'</strong>:'.$single_auto->userId.'；<br/>';
            }
            return $auto_str;
        });

        $states = [
            1 => ['value' => '1', 'text' => '打开', 'color' => 'primary'],
            0 => ['value' => '0', 'text' => '关闭', 'color' => 'default'],
        ];
        $grid->column('recyclable', __('是否重复'))->switch($states);
        $grid->column('status', __('启用状态'))->switch($states);
        $grid->column('auto_distribute_status', __('自动分配状态'))->switch($states);
        $grid->column('enable_time', __('开始工作时间'))->editable();
        $grid->column('disbale_time', __('结束工作时间'))->editable();
//        $grid->column('created_at', __('创建时间'));
//        $grid->column('updated_at', __('更新时间'));

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $filter->column(6/10, function ($filter) {
                $filter->equal('belong', '所属');
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
        $show = new Show(ResDistributionConfig::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('belong', __('Belong'));
        $show->field('recyclable_list', __('Recyclable list'));
        $show->field('active_list', __('Active list'));
        $show->field('except_list', __('排除列表'));
        $show->field('recyclable', __('Recyclable'));
        $show->field('status', __('Status'));
        $show->field('enable_time', __('Enable time'));
        $show->field('disbale_time', __('Disbale time'));
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
        $form = new Form(new ResDistributionConfig());
        $form->hidden('belong', __('所属'));

        $form->radio('nationality', '分组展示')
            ->options([
                1 => '排除用户列表',
                3 => '可循环的列表',
                2 => '使用中的列表',
                4 => '其他',
                5 => '排除账号的自动分配',
            ])->when(1, function (Form $form) {
                $users = DB::table('ec_users')->where('status','=','1')->get()->toArray();
                $depts = DB::table('ec_depts')->get()->toArray();
                $complete_depts = [];
                foreach ($depts as $dept){
                    $complete_depts[$dept->deptId] = ['deptName'=>$dept->deptName,'parentDeptId'=>$dept->parentDeptId];
                }

                $finish_user_arr = [];
                foreach ($users as $user){
                    //判断deptId是否为0
                    if ($user->deptId == '0'){
                        //部门为0，直接当前记录的部门值赋值
                        $finish_user_arr[$user->userId] = $user->title.'-'.$user->userName;
                    }else{
                        //部门ID不为0，调用接口去循环部门
                        $dept_PreName_arr = $this->get_user_depts([],$user->deptId,$complete_depts);
                        $finish_user_arr[$user->userId] = implode('-',array_reverse($dept_PreName_arr)).'-'.$user->title.'-'.$user->userName;
                    }
                }

                $form->listbox('except_list', __('排除列表'))->options($finish_user_arr);

            })->when(2, function (Form $form) {

                $form->keyValue('active_list', __('使用中的列表'));


            })->when(3, function (Form $form) {

//                $form->html('你的html内容');
                $form->keyValue('recyclable_list', __('可循环的列表'));

            })->when(4, function (Form $form) {

                $form->switch('recyclable', __('是否循环'));
                $form->switch('status', __('启用状态'));
                $form->switch('auto_distribute_status', __('自动分配状态'));
                $form->text('belong', __('所属'));
                $form->text('enable_time', __('开始营业时间'));
                $form->text('disbale_time', __('结束营业时间'));

            })->when(5, function (Form $form) {
//                $user_obj = Auth::guard('admin')->user();
                $res_config_account_arr = DB::table('res_config')->where('status','=','1')->get();
                $finish_account_arr = [];
                if (empty($res_config_account_arr)){
                    $finish_account_arr = [];
                }else{
                    foreach ($res_config_account_arr as $res_config_account){
                        $finish_account_arr[$res_config_account->id] = $res_config_account->custom_name.'-('.$res_config_account->belong.'-'.$res_config_account->type.')';
                    }
                }
                $form->listbox('except_auto_account_list', __('排除账号自动分配列表'))->options($finish_account_arr);

            })->default(1);

        $form->ignore(['nationality',]);

        return $form;
    }

    public function get_user_depts($res,$deptId,$depts){
        //第一步直接根据部门ID取部门值
        $res[] = $depts[$deptId]['deptName'];

        //判断对否还有父部门
        if ($depts[$deptId]['parentDeptId'] == '0'){
            return $res;
        }else{
            return $this->get_user_depts($res,$depts[$deptId]['parentDeptId'],$depts);
        }

    }
}
