<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

use App\Models\Product;
use Illuminate\Http\Request;
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

//Public API
Route::post('/user/create',[UserController::class,'store']);
Route::post('/user/login',[AuthController::class,'login']);

Route::post('/password/forgot',[NewPasswordController::class,'forgotPassword']);
Route::post('/password/reset',[NewPasswordController::class,'reset']);

//Private API
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/user/logout',[AuthController::class,'logout']);
    Route::put('/user/update',[UserController::class,'update']);

    Route::get('/products',[ProductController::class,'index']);
    Route::get('/product/show/{product:slug}',[ProductController::class,'show']);
    Route::post('/product/create',[ProductController::class,'store']);
    Route::post('/product/update/{product:slug}',[ProductController::class,'update']);
    Route::post('/product/delete/{product:slug}',[ProductController::class,'destroy']);
 
    Route::post('/category/create',[CategoryController::class,'store']);
    Route::post('/category/update/{category:slug}',[CategoryController::class,'update']);
    Route::post('/category/delete/{category:slug}',[CategoryController::class,'destroy']);

    Route::post('/carts',[CartController::class,'index']);
    Route::post('/cart/add',[CartController::class,'store']);
    Route::post('/cart/delete',[CartController::class,'destroy']); 
    Route::post('/cart/clear',[CartController::class,'clear']); 
    Route::post('/cart/check',[CartController::class,'check']); 
    Route::post('/cart/checkout',[CartController::class,'checkout']); 
});