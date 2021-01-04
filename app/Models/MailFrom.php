<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailFrom extends Model
{
    use HasFactory;
    protected $table = 'mail_from';
    protected $primaryKey = 'id';
}
