<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WechatSearchImage extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    protected $table = 'wechat_search_img';
    protected $primaryKey = 'id';
}
