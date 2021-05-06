<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResDistributionConfig extends Model
{
    use HasFactory;
    protected $table = 'res_distribution_config';
    protected $primaryKey = 'id';
    protected $casts = [
        'recyclable_list' => 'json',
        'active_list' => 'json',
        'except_list' => 'json',
    ];
    public function getRecyclableListAttribute($value1)
    {
        return json_decode($value1, true) ?: [];
    }

    public function setRecyclableListAttribute($value1)
    {
        logger('setRecyclableListAttribute',$value1);
        $this->attributes['recyclable_list'] = json_encode($value1);
    }
    public function getActiveListAttribute($value2)
    {
        return json_decode($value2, true) ?: [];
    }

    public function setActiveListAttribute($value2)
    {
        $this->attributes['active_list'] = json_encode($value2);
    }


    public function getExceptListAttribute($value3)
    {
        return array_values(json_decode($value3, true) ?: []);
    }

    public function setExceptListAttribute($value3)
    {
        $this->attributes['except_list'] = json_encode(array_values($value3));
    }

    public function getExceptAutoAccountListAttribute($value4)
    {
        return array_values(json_decode($value4, true) ?: []);
    }

    public function setExceptAutoAccountListAttribute($value4)
    {
        $this->attributes['except_auto_account_list'] = json_encode(array_values($value4));
    }


}
