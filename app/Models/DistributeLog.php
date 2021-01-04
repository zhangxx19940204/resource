<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributeLog extends Model
{
    use HasFactory;
    protected $table = 'res_distribution_log';
    protected $primaryKey = 'id';

    public function ecUser()
    {
        return $this->belongsTo('App\Models\EcUser','ec_userId','userId');
    }
}
