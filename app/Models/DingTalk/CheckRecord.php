<?php

namespace App\Models\DingTalk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckRecord extends Model
{
    use HasFactory;
    protected $table = 'dingding_user_checkrecord';

    public function ding_user_info(){
        return $this->belongsTo('App\Models\DingTalk\DingTalkUser','user_id','userid');
    }
}
