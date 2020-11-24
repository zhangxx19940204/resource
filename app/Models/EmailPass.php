<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailPass extends Model
{
    //
    use HasFactory;
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'email_pass';

}
