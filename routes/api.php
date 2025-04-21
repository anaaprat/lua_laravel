<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/bars', [AdminController::class, 'bars']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/movements', [AdminController::class, 'movements']);

    //Bares
    Route::post('/bars', [AdminController::class, 'storeBar']);
    Route::get('/bars/{id}', [AdminController::class, 'showBar']);
    Route::put('/bars/{id}', [AdminController::class, 'updateBar']);
    Route::delete('/bars/{id}', [AdminController::class, 'deleteBar']);
    
    // Usuarios normales (clientes)
    Route::get('/users', [AdminController::class, 'listUsers']);
    Route::get('/users/{id}', [AdminController::class, 'showUser']);
    Route::post('/users', [AdminController::class, 'storeUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

});