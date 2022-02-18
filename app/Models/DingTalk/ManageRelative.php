<?php

namespace App\Models\DingTalk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ManageRelative extends Model
{
    use HasFactory;
    protected $table = 'dingding_manage_relative';
    protected $primaryKey = 'id';
    protected $casts = [
        'member_id_list' => 'json',
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
