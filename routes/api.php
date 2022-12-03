<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AuthenticateJWT;
use App\Models\ArticleModel;
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
    Route::get('/categories', [CategoryController::class, 'get']);
    Route::get('/category/{id}', [CategoryController::class, 'getById']);
    Route::put('/category', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    //Article Activity
    Route::post('/article', [ArticleController::class, 'store']);
    Route::get('/articles', [ArticleController::class, 'get']);
    Route::get('/articles/{slug}', [ArticleController::class, 'getBySlug']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    Route::post('/articles', [ArticleController::class, 'update']);
});

Route::get('/published/articles', [ArticleController::class, 'getPublished']);
Route::get('/published/articles/{category_id}', [ArticleController::class, 'getPublishedByCategory']);