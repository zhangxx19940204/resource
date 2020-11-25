<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResData;
use App\Models\ResConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\Auth;

class ResDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '资源统计';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResData());

        $user_obj = Auth::guard('admin')->user();

        $grid->header(function ($query) {
            $user_obj = Auth::guard('admin')->user();

            return '用户ID：'.$user_obj->id;
        });

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('用户ID'));
        $grid->column('config_id', __('所属账号'))->display(function (){
            return $this->configData->custom_name;
        });

        $grid->column('data_name', __('姓名'));
        $grid->column('data_phone', __('电话'));
        $grid->column('belong', __('所属'));
        $grid->column('type', __('类型'));

        $grid->column('fastHorse_id', __('快马ID / 头条来源'))->display(function (){
            if ($this->type == '快马'){
                $data_arr = json_decode($this->data_json,true);
                return $data_arr['id'];
            }if ($this->type == '头条'){
                $data_arr = json_decode($this->data_json,true);
                return $data_arr['app_name'];
            }else{
                return '';
            }

        });

        $grid->column('created_at', __('入库时间'))->display(function ($created_at){
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at){
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });

        $grid->column('remarks', __('备注'));



        $grid->column('data_json', __('源数据'))->display(function (){
            return '点击查看详细数据';
        })->modal('数据源数据', function ($model) {
            $data_arr= json_decode($model->data_json,true);
            $key_arr = array_keys($data_arr);
            $data_val = [];
            foreach ($key_arr as $k=>$value){
                //判断值是否为其他类型
                if (is_array($data_arr[$value])){
                    //当前值为数组
                    $data_val[] = ['key'=>$value,'value'=>implode(';',((array) $data_arr[$value]))];
                }else{
                    //不是数组直接赋值
                    $data_val[] = ['key'=>$value,'value'=>$data_arr[$value]];
                }
            }
            return new Table(['名称','值'], $data_val);

        });

        $grid->model()->orderBy('id', 'desc');
        if ($user_obj->id == 1) {
            // 不加 用户id的限制
        } else {
            $grid->model()->whereIn('user_id', [$user_obj->id]);
        }

        $grid->disableCreateButton();

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏

            $filter->column(5/10, function ($filter) {

                $user_obj = Auth::guard('admin')->user();
                if ($user_obj->id == 1){
                    //超级管理员
                    $config_data = ResConfig::get()->toarray();
                }else{
                    $config_data = ResConfig::get()->where('user_id',$user_obj->id)->toarray();
                }
                $config_arr = [];
                foreach ($config_data as $key=>$config_data){
                    $config_arr[$config_data['id']] = $config_data['custom_name'];
                }


                $filter->in('config_id', '账户信息')->multipleSelect($config_arr);
                $filter->in('belong', '所属')->multipleSelect(['半城外'=>'半城外','阿城'=>'阿城']);
                // 设置created_at字段的范围查询
                $filter->between('created_at', '创建时间')->datetime();
            });

            $filter->column(5/10, function ($filter) {
                $filter->in('type', '类型')->multipleSelect(['头条'=>'头条','快马'=>'快马','全球'=>'全球','23网'=>'23网']);
                $filter->like('data_phone', '客户电话');
                $filter->like('data_name', '客户姓名');
                $filter->like('data_json', '源数据查询');
                $filter->between('updated_at', '更新时间')->datetime();
            });


        });

        //导出配置
        $grid->export(function ($export) {

            $export->filename(date('Y-m-d H:i:s').'-资源统计.csv');

            $export->only(['belong','type','config_id','data_name','data_phone','created_at','fastHorse_id']);

            $export->column('created_at', function ($value, $original) {
                return $value;
            });
            $export->column('config_id', function ($value, $original) {
                return $value;
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
        $show = new Show(ResData::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('config_id', __('Config id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('last_para', __('Last para'));
        $show->field('remarks', __('Remarks'));
        $show->field('belong', __('Belong'));
        $show->field('type', __('Type'));
        $show->field('data_json', __('Data json'));
        $show->field('data_name', __('Data name'));
        $show->field('data_phone', __('Data phone'));
        $show->field('data_request_id', __('Data request id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ResData());

        $form->number('user_id', __('User id'));
        $form->number('config_id', __('Config id'));
        $form->text('last_para', __('Last para'));
        $form->text('remarks', __('Remarks'));
        $form->text('belong', __('Belong'));
        $form->text('type', __('Type'));
        $form->text('data_json', __('Data json'));
        $form->text('data_name', __('Data name'));
        $form->text('data_phone', __('Data phone'));
        $form->text('data_request_id', __('Data request id'));

        return $form;
    }
}