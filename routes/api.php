<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RankingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/bars', [BarController::class, 'index']);
Route::post('/bars/token', [BarController::class, 'getByToken']);
Route::get('/bars/{barId}/products', [BarController::class, 'getProducts']);
Route::get('/bars/{barId}/products/{productId}', [ProductController::class, 'show']);
Route::get('/bars/{barId}/products/search', [ProductController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::put('/user', [UserController::class, 'update']);
    Route::get('/user/credit-history', [UserController::class, 'creditHistory']);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'history']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::post('/rankings', [RankingController::class, 'create']);
    Route::post('/rankings/join', [RankingController::class, 'join']);
    Route::get('/rankings', [RankingController::class, 'myRankings']);
    Route::get('/rankings/{id}', [RankingController::class, 'show']);

    Route::post('/admin/rankings/reset', [RankingController::class, 'resetMonthly']);

});
