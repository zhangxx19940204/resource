<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResData extends Model
{
    use HasFactory;

    protected $table = 'res_data';
    protected $primaryKey = 'id';

    /**
     * 获取该数据的所属配置
     */
    public function configData()
    {
        return $this->belongsTo('App\Models\ResConfig','config_id','id');
    }
}
