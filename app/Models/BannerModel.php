<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerModel extends Model
{
    use HasFactory;
    protected $table = 'banners';
    protected $fillable = [ 
        'title', 
        'desc', 
        'image_uri',
        'image_url',
    ];
}