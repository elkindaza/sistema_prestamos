<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\PrestamosController;

Route::get('/', function () {
    // Si está logueado -> dashboard, si no -> login
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});


Route::middleware(['auth'])->group(function () {



    // Dashboard general (puedes mantenerlo como landing post-login)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 🔒 Admin (todo lo que edita/crea)
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/inicio', [DashboardController::class, 'index'])->name('admin.inicio');
        Route::resource('clientes', ClienteController::class);
        Route::resource('prestamos', PrestamosController::class)->names('admin.prestamos');
        Route::post('prestamos/{prestamo}/aprobar', [PrestamosController::class, 'aprobar'])->name('admin.prestamos.aprobar');
        Route::post('prestamos/{prestamo}/desembolsar', [PrestamosController::class, 'desembolsar'])->name('admin.prestamos.desembolsar');
        Route::resource('cuotas', \App\Http\Controllers\Admin\CuotasController::class)
            ->names('admin.cuotas');
    });

    // 👀 Panel lectura (asociados y admin si quieres)
    Route::prefix('panel')->group(function () {
        Route::view('/resumen', 'panel.resumen')->name('panel.resumen');
        // aquí luego van reportes / consultas
    });

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
