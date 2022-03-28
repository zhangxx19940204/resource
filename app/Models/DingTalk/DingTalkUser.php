<?php

namespace App\Models\DingTalk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DingTalkUser extends Model
{
    use HasFactory;
    protected $table = 'dingding_user';

    public function checkRecord()
    {
        return $this->hasMany('App\Models\DingTalk\CheckRecord','userid','user_id');
    }
}
