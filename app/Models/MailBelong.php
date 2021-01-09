<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailBelong extends Model
{
    use HasFactory;
    protected $table = 'mail_belong';
    protected $primaryKey = 'id';
}
