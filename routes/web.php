<?php

use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

    Route::post('/regenerate-my-qr', function () {
        $user = auth()->user();
        $debugInfo = [];

        try {
            $debugInfo[] = "User: " . $user->name;
            $debugInfo[] = "Token: " . $user->token;

            $qr = QrCode::format('svg')->size(300)->generate($user->token);
            $debugInfo[] = "QR generated successfully";

            $filePath = 'qrs/bar_' . $user->name . '.svg';
            $debugInfo[] = "File path: " . $filePath;

            $qrDir = storage_path('app/public/qrs');
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0755, true);
                $debugInfo[] = "Created directory: " . $qrDir;
            }

            $result = Storage::disk('public')->put($filePath, $qr);
            $debugInfo[] = "Storage result: " . ($result ? 'SUCCESS' : 'FAILED');

            if ($result) {
                $user->qr_path = $filePath;
                $user->save();
                $debugInfo[] = "User updated successfully";
            }

        } catch (Exception $e) {
            $debugInfo[] = "ERROR: " . $e->getMessage();
        }

        // Mostrar debug info temporalmente
        return redirect()->back()->with('debug', implode('<br>', $debugInfo));
    })->name('regenerate-qr')->middleware('auth');

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

Route::get('/debug-symlink', function () {
    $publicStorage = public_path('storage');
    $storagePublic = storage_path('app/public');

    echo "Public storage path: " . $publicStorage . "<br>";
    echo "Storage public path: " . $storagePublic . "<br>";
    echo "Public storage exists: " . (file_exists($publicStorage) ? 'YES' : 'NO') . "<br>";
    echo "Is symlink: " . (is_link($publicStorage) ? 'YES' : 'NO') . "<br>";

    if (is_link($publicStorage)) {
        echo "Symlink target: " . readlink($publicStorage) . "<br>";
        echo "Target exists: " . (file_exists(readlink($publicStorage)) ? 'YES' : 'NO') . "<br>";
    }

    // Verificar archivo específico
    $qrFile = $storagePublic . '/qrs/bar_lua10.svg';
    echo "QR file exists in storage: " . (file_exists($qrFile) ? 'YES' : 'NO') . "<br>";

    return "";
});

Route::get('/railway-setup', function () {
    try {
        // Eliminar carpeta storage (no enlace)
        $publicStorage = public_path('storage');
        if (file_exists($publicStorage)) {
            // Eliminar recursivamente toda la carpeta
            function deleteDirectory($dir)
            {
                if (!file_exists($dir))
                    return true;
                if (!is_dir($dir))
                    return unlink($dir);
                foreach (scandir($dir) as $item) {
                    if ($item == '.' || $item == '..')
                        continue;
                    if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
                        return false;
                }
                return rmdir($dir);
            }
            deleteDirectory($publicStorage);
            echo "✅ Removed old storage directory<br>";
        }

        // Crear enlace simbólico
        Artisan::call('storage:link', ['--force' => true]);
        echo "✅ Storage symlink created<br>";

        // Verificar que es enlace
        if (is_link($publicStorage)) {
            echo "✅ Confirmed: Is now a symlink<br>";
        } else {
            echo "❌ Still not a symlink<br>";
        }

        return "Setup complete!";
    } catch (Exception $e) {
        return "❌Error: " . $e->getMessage();
    }
});

Route::get('/fix-everything', function () {
    try {
        // Crear directorio si no existe
        $qrDir = storage_path('app/public/qrs');
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        // Forzar recrear enlace
        $publicStorage = public_path('storage');
        if (file_exists($publicStorage)) {
            if (is_dir($publicStorage)) {
                rmdir($publicStorage);
            }
        }

        // Crear enlace manualmente
        symlink(storage_path('app/public'), $publicStorage);

        // Regenerar QR del usuario actual
        $user = auth()->user();
        $qr = QrCode::format('svg')->size(300)->generate($user->token);
        $filePath = 'qrs/bar_' . $user->name . '.svg';
        Storage::disk('public')->put($filePath, $qr);
        $user->qr_path = $filePath;
        $user->save();

        return "✅ Everything fixed!";

    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->middleware('auth');
