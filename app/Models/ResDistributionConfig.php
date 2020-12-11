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
    ];
    public function getRecyclableListAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setRecyclableListAttribute($value)
    {
        $this->attributes['recyclable_list'] = json_encode(array_values($value));
    }

}
