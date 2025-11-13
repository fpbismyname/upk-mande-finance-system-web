<?php

use App\Http\Controllers\Admin\AnggotaKelompokController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalPencairanController;
use App\Http\Controllers\Admin\KelompokController;
use App\Http\Controllers\Admin\PendanaanController;
use App\Http\Controllers\Admin\PengajuanPinjamanController;
use App\Http\Controllers\Admin\PinjamanKelompokController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->name('admin.')->group(function () {

    // Login routes
    Route::get('/login', [AuthController::class, 'admin_login_page'])->name('login');
    Route::post('/submit-login', [AuthController::class, 'submit_login'])->name('submit-login');
    Route::post('/logout', [AuthController::class, 'submit_logout'])->name('logout');

    // Dashboard admin internal
    Route::middleware([AdminMiddleware::class])->group(function () {
        // Dashboard Resource routes
        Route::resources([
            'dashboard' => DashboardController::class,
            'users' => UserController::class,
            'kelompok' => KelompokController::class,
            'kelompok.anggota-kelompok' => AnggotaKelompokController::class,
            'pendanaan' => PendanaanController::class,
            'pengajuan-pinjaman' => PengajuanPinjamanController::class,
            'jadwal-pencairan' => JadwalPencairanController::class,
            'pinjaman-kelompok' => PinjamanKelompokController::class,
        ]);

        // Pendanaan action routes
        Route::prefix('/pendanaan')->name('pendanaan.')->group(function () {
            // Action routes
            Route::post('/tambah-saldo', [PendanaanController::class, 'tambah_saldo'])->name('tambah-saldo');
            Route::post('/tarik-saldo', [PendanaanController::class, 'tarik_saldo'])->name('tarik-saldo');
        });

        // Settings routes
        Route::prefix("/settings")->name('settings.')->group(function () {
            // View routes
            Route::get('/', [SettingsController::class, 'index'])->name('view');
            // Action routes
            Route::put('/save-changes', [SettingsController::class, 'save_changes'])->name('save-changes');
        });
    });
});
