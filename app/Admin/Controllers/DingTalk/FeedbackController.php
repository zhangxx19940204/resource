<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\Feedback;
use App\Models\DingTalk\DingTalkUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid->column('phone', __('手机号'));
        $grid->column('feedback_short', __('反馈'));
        $grid->column('feedback_detail', __('跟进记录'))->width(700);

        $grid->export(function ($export) {

            $export->filename('资源反馈'.time().'.csv');

            // $export->except(['column1', 'column2']);

            $export->only(['dingding_user_id', 'blong', 'data_date', 'phone', 'feedback_short', 'feedback_detail']);

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


                $filter->in('blong', '品牌所属')->multipleSelect(['腩潮鲜' => '腩潮鲜','半城外' => '半城外','下江腩'=>'下江腩','原时烤肉' => '原时烤肉','阿城牛货'=>'阿城牛货','隐匠'=>'隐匠']);
                $filter->between('data_date', '日期')->date();
                $feedback_short_arr = ['简短反馈'=>'简短反馈','意向客户'=>'意向客户','正常咨询'=>'正常咨询','已加微信发资料'=>'已加微信发资料','在忙，加微信'=>'在忙，加微信','预约回电'=>'预约回电','多次未接（3次及以上）'=>'多次未接（3次及以上）','未接'=>'未接','接了就挂'=>'接了就挂','未咨询，被黑'=>'未咨询，被黑','关机'=>'关机','停机'=>'停机','空号'=>'空号','没钱，费用接受不了'=>'没钱，费用接受不了','公海资源'=>'公海资源','重复他人'=>'重复他人','无意向'=>'无意向','同行'=>'同行','学技术买设备'=>'学技术买设备','推广推销'=>'推广推销'];
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
