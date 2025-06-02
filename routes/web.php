<?php

use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\BarController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\StatisticsController;
use App\Http\Controllers\Web\BarProductController;
use App\Http\Controllers\Web\RechargeController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Rutas de recuperación de contraseña
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');


Route::middleware(['auth', 'role:bar'])->group(function () {
    Route::get('/bar', [BarController::class, 'dashboard'])->name('bar.dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('bar.statistics');
    Route::get('/bar-products', [BarProductController::class, 'index'])->name('bar-products.index');
    Route::get('/bar-products/create', [BarProductController::class, 'create'])->name('bar-products.create');
    Route::post('/bar-products', [BarProductController::class, 'store'])->name('bar-products.store');
    Route::get('/bar-products/{barProduct}/edit', [BarProductController::class, 'edit'])->name('bar-products.edit');
    Route::put('/bar-products/{barProduct}', [BarProductController::class, 'update'])->name('bar-products.update');
    Route::delete('/bar-products/{barProduct}', [BarProductController::class, 'destroy'])->name('bar-products.destroy');
    Route::get('/bar/recharges-user', [RechargeController::class, 'rechargesUser'])->name('bar.rechargesUser');
    Route::post('/bar/add-credit', [RechargeController::class, 'addCredit'])->name('bar.addCredit');
    Route::get('/bar/account', [App\Http\Controllers\Web\BarAccountController::class, 'show'])->name('bar.account');
    Route::post('/bar/account/update', [App\Http\Controllers\Web\BarAccountController::class, 'update'])->name('bar.account.update');
    Route::post('/bar/account/update-password', [App\Http\Controllers\Web\BarAccountController::class, 'updatePassword'])->name('bar.account.updatePassword');

    Route::post('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
    Route::post('/orders/{order}/pending', [OrderController::class, 'markAsPending'])->name('orders.pending');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/bar/recharges', [RechargeController::class, 'index'])->name('bar.recharges');

    Route::get('/bar/orders/ajax', [BarController::class, 'getOrdersAjax'])->name('bar.orders.ajax');

});

// Rutas ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');

    Route::get('/bars', [AdminController::class, 'bars'])->name('bars');
    Route::get('/bars/create', [AdminController::class, 'createBar'])->name('bars.create');
    Route::post('/bars', [AdminController::class, 'storeBar'])->name('bars.store');
    Route::get('/bars/{id}/edit', [AdminController::class, 'editBar'])->name('bars.edit');
    Route::put('/bars/{id}', [AdminController::class, 'updateBar'])->name('bars.update');
    Route::delete('/bars/{id}', [AdminController::class, 'deleteBar'])->name('bars.delete');
    Route::patch('/bars/{id}/toggle-status', [AdminController::class, 'toggleBarStatus'])->name('bars.toggle-status');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    Route::get('/movements', [AdminController::class, 'movements'])->name('movements');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');

    Route::get('/rankings', [AdminController::class, 'rankings'])->name('rankings');
    Route::get('/rankings/create', [AdminController::class, 'createRanking'])->name('rankings.create');
    Route::post('/rankings', [AdminController::class, 'storeRanking'])->name('rankings.store');
    Route::get('/rankings/{id}', [AdminController::class, 'showRanking'])->name('rankings.show');
    Route::get('/rankings/{id}/edit', [AdminController::class, 'editRanking'])->name('rankings.edit');
    Route::put('/rankings/{id}', [AdminController::class, 'updateRanking'])->name('rankings.update');
    Route::delete('/rankings/{id}', [AdminController::class, 'deleteRanking'])->name('rankings.delete');
    Route::patch('/rankings/{id}/reset', [AdminController::class, 'resetRankingPoints'])->name('rankings.reset');
    Route::post('/rankings/reset-all', [AdminController::class, 'resetAllRankings'])->name('rankings.reset-all');

});
