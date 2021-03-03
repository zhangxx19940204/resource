<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResConfig extends Model
{
    use HasFactory;
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'res_config';
    protected $primaryKey = 'id';

    /**
     * 获取配置信息的相关数据
     */
    public function resData()
    {
        return $this->hasMany('App\Models\ResData','id','config_id');
    }
}
