<?php

namespace App\Models\CustomerService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $table = 'customerservice_record';

    /**
     * 获取该数据的所属配置
     */
    public function configData()
    {
        return $this->belongsTo('App\Models\CustomerService\Config','config_id','id');
    }
}
