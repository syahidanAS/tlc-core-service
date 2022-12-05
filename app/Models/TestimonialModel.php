<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestimonialModel extends Model
{
    use HasFactory;
    protected $table = 'testimonials';
    protected $fillable = [ 
        'name',
        'testimonial' ,
        'image_uri',
        'image_url'
    ];
}