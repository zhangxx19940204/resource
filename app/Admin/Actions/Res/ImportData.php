<?php

namespace App\Admin\Actions\Res;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ResDataImport;
use Mockery\Exception;
use Psy\Exception\ErrorException;
use Illuminate\Support\Facades\Storage;

class ImportData extends Action
{
    public $name = '导入数据';

    protected $selector = '.import-post';

    public function handle(Request $request)
    {
        // 下面的代码获取到上传的文件，然后使用`maatwebsite/excel`等包来处理上传你的文件，保存到数据库
        $file = $request->file('res_file');
        $file_path = $file->getRealPath();
        $extension = $file->getClientOriginalExtension();
        $fileName = '/'.date('Ymd').'/'.time().'.'.$extension;
        $result = Storage::disk('public')->put($fileName, file_get_contents($file_path));
        //上传excel文件到服务器
        //上传完毕，进行数据的读取和存储
        $file_storage_path = storage_path($fileName);
        $excel_all_data = Excel::toArray(new ResDataImport, $file_storage_path);//读取本地的文件，将数据投放给excel类去处理
//        var_dump($file_storage_path,$excel_all_data);
//        die();
        $excel_data = $excel_all_data[0];
        $head_data = $excel_data[0];
        unset($excel_data[0]);
        $insert_data = [];
        foreach ($excel_data as $single_data){
            $basic_data = [];
            foreach ($single_data as $k=>$v){
                $basic_data[$head_data[$k]] = $v;
            }
            $config = DB::table('res_config')->where('id','=',$basic_data['config_id'])->first();
            $basic_data['user_id'] = $config->user_id;
            $basic_data['created_at'] = date('Y-m-d H:i:s');
            $basic_data['updated_at'] = date('Y-m-d H:i:s');
            $basic_data['belong'] = $config->belong;
            $basic_data['type'] = $config->type;
            $basic_data['remarks'] = $config->remarks;
            $basic_data['data_json'] = json_encode([]);
            $insert_data[] = $basic_data;

        }

//        var_dump(json_encode($insert_data));
//        var_dump($file_path,$extension,$result,$file_storage_path);
        try {
            DB::beginTransaction();
            DB::table('res_data')->insert($insert_data);
            DB::commit();
        }catch (\mysql_xdevapi\Exception $e){
            DB::rollBack();
        }

        return $this->response()->success('导入完成！')->refresh();
    }

    public function form()
    {
        $this->file('res_file', '请选择文件');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-post"><i class="fa fa-upload"></i> 导入数据</a> &nbsp;
        <a target="_blank" href="/导入表格模板.xlsx"> 点击下载导入模板</a>
HTML;
    }
}
