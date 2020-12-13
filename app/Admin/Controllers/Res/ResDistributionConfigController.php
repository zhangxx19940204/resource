<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResDistributionConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
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
        $grid->column('recyclable_list', __('可重复使用列表'));
        $grid->column('active_list', __('正在使用的列表'));
        $grid->column('except_list', __('排除列表'));

        $states = [
            1 => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            0 => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];
        $grid->column('recyclable', __('是否重复'))->switch($states);
        $grid->column('status', __('启用状态'))->switch($states);
//        $grid->column('enable_time', __('Enable time'));
//        $grid->column('disbale_time', __('Disbale time'));
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
                1 => '可循环的列表',
                2 => '使用中的列表',
                3 => '排除列表',
                4 => '其他',
            ])->when(1, function (Form $form) {

                $form->keyValue('recyclable_list', __('可循环的列表'));

            })->when(2, function (Form $form) {

                $form->keyValue('active_list', __('使用中的列表'));


            })->when(3, function (Form $form) {

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

            })->when(4, function (Form $form) {

                $form->switch('recyclable', __('是否循环'));
                $form->switch('status', __('启用状态'));

            })->default(1);


        $form->ignore(['nationality',]);



//        $form->text('enable_time', __('Enable time'));
//        $form->text('disbale_time', __('Disbale time'));
//

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
