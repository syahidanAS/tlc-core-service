<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TestimonialController;
use App\Http\Middleware\AuthenticateJWT;
use App\Models\ArticleModel;
use App\Models\FaqModel;
use App\Models\TestimonialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth', 'as'=> ''], function(){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});
Route::middleware([AuthenticateJWT::class])->group(function(){
    //Category 
    Route::get('/me', [AuthController::class,'me']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{id}', [CategoryController::class, 'getById']);
    Route::put('/category', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    //Article Activity
    Route::post('/article', [ArticleController::class, 'store']);
    Route::get('/articles/{slug}', [ArticleController::class, 'getBySlug']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    Route::post('/articles', [ArticleController::class, 'update']);
    Route::get('/search', [ArticleController::class, 'universalSearch']);

    //FAQs Activity
    Route::post('/faqs', [FaqController::class, 'store']);
    Route::get('/faqs', [FaqController::class, 'get']);
    Route::delete('/faqs/{id}', [FaqController::class, 'destroy']);
    Route::put('/faqs', [FaqController::class, 'update']);

    //Testimonials Activity
    Route::post('/testimonials', [TestimonialController::class,'store']);
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);
    Route::post('/update/testimonial', [TestimonialController::class, 'update']);

    //Banners Activity
    Route::post('/banners', [BannerController::class, 'store']);
    Route::delete('/banners/{id}', [BannerController::class, 'destroy']);
});


//Categories
Route::get('/categories', [CategoryController::class, 'get']);

//Articles
Route::get('/articles', [ArticleController::class, 'get']);
Route::get('/published/articles', [ArticleController::class, 'getPublished']);
Route::get('/published-articles/search', [ArticleController::class, 'publishedSearch']);
Route::get('/published/articles/{category_id}', [ArticleController::class, 'getPublishedByCategory']);

// Search FAQs
Route::get('/faqs/search', [FaqController::class, 'search']);


//Testimonials
Route::get('/testimonials', [TestimonialController::class, 'index']);


//Banners
Route::get('/banners', [BannerController::class, 'index']);