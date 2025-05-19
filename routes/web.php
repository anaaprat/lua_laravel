<?php

use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\web\BarController;
use App\Http\Controllers\web\OrderController;
use App\Http\Controllers\web\StatisticsController;
use App\Http\Controllers\web\BarProductController;
use App\Http\Controllers\web\RechargeController;


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
    // Información básica
    $info = [
        'Laravel Version' => app()->version(),
        'PHP Version' => phpversion(),
        'Environment' => app()->environment(),
        'Debug Mode' => config('app.debug'),
        'Base Path' => base_path(),
        'Views Path' => resource_path('views'),
        'Login View Path' => resource_path('views/auth/login.blade.php'),
        'Login View Exists' => file_exists(resource_path('views/auth/login.blade.php')),
        'Route List' => array_map(function ($route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'name' => $route->getName()
            ];
        }, app('router')->getRoutes()->getRoutes()),
    ];

    // Devolver como JSON
    return response()->json($info);

    // Comentamos la vista original para evitar problemas
    // return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


Route::middleware(['auth', 'role:bar'])->group(function () {
    Route::get('/bar', [BarController::class, 'dashboard'])->name('bar.dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('bar.statistics');

    //Gestion de los products
    Route::get('/bar-products', [BarProductController::class, 'index'])->name('bar-products.index');
    Route::get('/bar-products/create', [BarProductController::class, 'create'])->name('bar-products.create');
    Route::post('/bar-products', [BarProductController::class, 'store'])->name('bar-products.store');
    Route::get('/bar-products/{barProduct}/edit', [BarProductController::class, 'edit'])->name('bar-products.edit');
    Route::put('/bar-products/{barProduct}', [BarProductController::class, 'update'])->name('bar-products.update');
    Route::delete('/bar-products/{barProduct}', [BarProductController::class, 'destroy'])->name('bar-products.destroy');
});

Route::post('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
Route::post('/orders/{order}/pending', [OrderController::class, 'markAsPending'])->name('orders.pending');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/bar/recharges', [RechargeController::class, 'index'])->name('bar.recharges');

// Ruta para depurar el sistema de vistas
Route::get('/debug-views', function () {
    // Ver configuración actual de vistas
    $viewConfig = config('view');

    // Comprobar ubicación y existencia de vistas específicas
    $loginViewPath = resource_path('views/auth/login.blade.php');
    $loginExists = file_exists($loginViewPath);
    $loginContent = $loginExists ? substr(file_get_contents($loginViewPath), 0, 200) : 'No existe';

    // Probar carga directa
    $viewLoader = app('view')->getFinder();
    try {
        $locatedPath = $viewLoader->find('auth.login');
        $viewLoaderWorks = true;
        $locatedPathResult = $locatedPath;
    } catch (\Exception $e) {
        $viewLoaderWorks = false;
        $locatedPathResult = $e->getMessage();
    }

    // Devolver toda la información
    return response()->json([
        'view_config' => $viewConfig,
        'auth_login_path' => $loginViewPath,
        'auth_login_exists' => $loginExists,
        'auth_login_content' => $loginContent,
        'view_loader_works' => $viewLoaderWorks,
        'located_path_result' => $locatedPathResult,
        'laravel_version' => app()->version(),
    ]);
});