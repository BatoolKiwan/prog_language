<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LikeController;
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

Route::resource('/categories',CategoryController::class);
Route::resource('/products',ProductController::class);
Route::resource('/likes',LikeController::class);
Route::group(['middleware' => 'auth:sanctum'], function ()
 {
Route::post('/logout', [AuthController::class, 'logout']);});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/sorting', [ProductController::class, 'sort']);
Route::post('/search', [ProductController::class, 'search']);
Route::post('/comment/{id}', [ProductController::class, 'comment']);
Route::get('/showComment/{id}', [ProductController::class, 'showComment']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
     return $request->user();
});

