<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArticleModel extends Model
{
    use HasFactory;
    protected $table = 'user_article';
    protected $fillable = [ 
        'user_id', 'article_id'
    ];
}