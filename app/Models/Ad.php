<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'user_id', 
        'judul_iklan', 
        'gambar_iklan', 
        'klik', 
        'status'
    ];
}
