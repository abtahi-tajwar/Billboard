<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authtoken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_name' 
    ];
    use HasFactory;
}
