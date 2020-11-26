<?php

namespace App\Admin\Actions\Res;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Distribution extends RowAction
{
    public $name = '分配';

    public function handle(Model $model,Request $request)
    {
        // $model ...
        // 获取到表单中的`type`值
        $request->get('type');

        // 获取表单中的`reason`值
        $request->get('reason');
        return $this->response()->success('Success message.')->refresh();
    }

    public function form()
    {
        $users = DB::table('ec_users')->get()->toArray();
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

        $this->multipleSelect('ec_user', '招商')->options($finish_user_arr);
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

//    public function get_complete_user_list(){
//        static $i = 0;
//
//        echo $i . '';
//
//        $i++;
//
//        if($i<10){
//
//            get_complete_user_list();
//
//        }
//    }
}
