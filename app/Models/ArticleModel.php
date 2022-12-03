<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    use HasFactory;
    protected $table = 'articles';
    protected $fillable = [ 
        'title', 
        'body', 
        'slug',
        'image_uri',
        'image_url',
        'published_at'
    ];
}