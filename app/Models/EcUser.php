<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcUser extends Model
{
    use HasFactory;
    protected $table = 'ec_users';
    protected $primaryKey = 'id';

    public function distributeLog()
    {
        return $this->hasMany('App\Models\DistributeLog','userId','ec_userId');
    }
}
