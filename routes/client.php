<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\AnggotaController;
use App\Http\Controllers\Client\CicilanKelompokController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\HomepageController;
use App\Http\Controllers\Client\KelompokController;
use App\Http\Controllers\Client\PengajuanKeanggotaanController;
use App\Http\Controllers\Client\PengajuanPinjamanController;
use App\Http\Controllers\Client\PinjamanKelompokController;
use App\Http\Controllers\Client\SettingsController;
use App\Http\Middleware\ClientMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix("/")->name("client.")->group(function () {
    // Login views
    Route::get('/login', [AuthController::class, 'client_login_page'])->name('login');
    // Login Actions
    Route::post('/submit-login', [AuthController::class, 'submit_login'])->name('submit-login');
    // Register views
    Route::get('/register', [AuthController::class, 'client_register_page'])->name('register');
    // Register Actions
    Route::post('/submit-register', [AuthController::class, 'submit_register_client'])->name('submit-register');

    Route::post('/logout', [AuthController::class, 'submit_logout'])->name('logout');

    // Homepage Views
    Route::get("/", [HomepageController::class, 'index'])->name("homepage.index");
    Route::get('/syarat-dan-ketentuan', [HomepageController::class, 'syarat_dan_ketentuan'])->name('homepage.syarat-dan-ketentuan');

    // Dashboard Client internal
    Route::middleware([ClientMiddleware::class])->group(function () {
        // Resource routes
        Route::resources([
            'dashboard' => DashboardController::class,
            'kelompok' => KelompokController::class,
            'kelompok.anggota-kelompok' => AnggotaController::class,
            'pinjaman-kelompok' => PinjamanKelompokController::class,
            'pinjaman-kelompok.cicilan-kelompok' => CicilanKelompokController::class,
            'pengajuan-pinjaman' => PengajuanPinjamanController::class,
            'pengajuan-keanggotaan' => PengajuanKeanggotaanController::class,
        ]);

        // Pengajuan keanggotaan actions
        Route::prefix('/pengajuan-keanggotaan')->name('pengajuan-keanggotaan.')->group(function () {
            Route::post('/{id}/cancel-pengajuan', [PengajuanKeanggotaanController::class, 'cancel_pengajuan'])->name('cancel');
        });

        // Pengajuan pinjaman actionsn
        Route::prefix('/pengajuan-pinjaman')->name('pengajuan-pinjaman.')->group(function () {
            Route::post('/{id}/cancel-pengajuan', [PengajuanPinjamanController::class, 'cancel_pengajuan'])->name('cancel');
        });

        // Settings routes
        Route::prefix("/settings")->name('settings.')->group(function () {
            // View routes
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            // Action routes
            Route::put('/save-changes', [SettingsController::class, 'save_changes'])->name('save-changes');
        });

    });
});