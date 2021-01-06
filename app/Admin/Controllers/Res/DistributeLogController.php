<?php

namespace App\Admin\Controllers\Res;

use App\Models\DistributeLog;
use App\Models\EcUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class DistributeLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DistributeLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DistributeLog());

        $grid->column('id', __('编号'));
        $grid->column('ec_userId', __('分配人'))->display(function (){
            return $this->ecUser->userName;
        });
        $grid->column('failureCause', __('错误原因'));
        $grid->column('synchronize_para', __('同步参数'))->display(function (){
                return '点击查看';
            })->modal('数据源数据', function ($model) {
                $data_arr= json_decode($model->synchronize_para,true);
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
        $grid->column('synchronize_results', __('同步结果'))->bool();
        $grid->column('created_at', __('创建时间'))->display(function ($created_at){
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at){
            if (empty($updated_at)){
                return '';
            }
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->disableCreateButton();
        $grid->actions(function ($actions){
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏

            $filter->column(5/10, function ($filter) {

//                $user_obj = Auth::guard('admin')->user();

                // 设置created_at字段的范围查询

                $ecUser_data = EcUser::get()->toarray();

                $ecUser_arr = [];
                foreach ($ecUser_data as $key=>$ecUser){
                    $ecUser_arr[$ecUser['userId']] = $ecUser['deptName'];
                }

                $filter->in('ec_userId', '分配人')->multipleSelect($ecUser_arr);
                $filter->where(function ($query) {

                    $query->where('synchronize_para->mobile', "{$this->input}");

                }, '手机号');

                $filter->in('synchronize_results','同步结果')->checkbox([
                    '0'    => '失败',
                    '1'    => '成功',
                ]);
                $filter->between('created_at', '创建时间')->datetime();

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
        $show = new Show(DistributeLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ec_userId', __('Ec userId'));
        $show->field('failureCause', __('FailureCause'));
        $show->field('synchronize_para', __('Synchronize para'));
        $show->field('synchronize_results', __('Synchronize results'));
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
        $form = new Form(new DistributeLog());

        $form->number('ec_userId', __('Ec userId'));
        $form->text('failureCause', __('FailureCause'));
        $form->text('synchronize_para', __('Synchronize para'));
        $form->text('synchronize_results', __('Synchronize results'));

        return $form;
    }
}
