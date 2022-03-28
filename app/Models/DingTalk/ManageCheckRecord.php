<?php

namespace App\Models\DingTalk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageCheckRecord extends Model
{
    use HasFactory;
    protected $table = 'dingding_manage_checkrecord';
    protected $casts = [
        'employee' => 'json',
    ];
    public function ding_user_info(){
        return $this->belongsTo('App\Models\DingTalk\DingTalkUser','manage_user','userid');
    }
}
