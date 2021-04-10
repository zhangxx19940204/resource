<?php

namespace App\Models\CustomerService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $table = 'customerservice_config';

    /**
     * 获取配置信息的相关数据
     */
    public function recordData()
    {
        return $this->hasMany('App\Models\CustomerService\Record','id','config_id');
    }
}
