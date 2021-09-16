<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertise extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title',
        'Description',
        'Image',
        'ExpirationDate',
        'UserId'        
    ];

    public function user() {
        return $this->hasOne(User::class, 'UserId', 'Id');
    }
    public function locations() {
        return $this->belongsTo(AdvertiseLocation::class, 'AdvertiseId', 'Id');
    }
}
