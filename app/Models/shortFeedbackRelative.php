<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shortFeedbackRelative extends Model
{
    use HasFactory;
    protected $table = 'short_feedback_relative';
    protected $primaryKey = 'id';
    protected $casts = [
        'find_keywords_list' => 'json',
    ];
}
