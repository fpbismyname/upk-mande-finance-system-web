<?php

use App\Http\Controllers\Admin\AnggotaKelompokController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalPencairanController;
use App\Http\Controllers\Admin\KelompokController;
use App\Http\Controllers\Admin\PengajuanPinjamanController;
use App\Http\Controllers\Admin\PinjamanKelompokController;
use App\Http\Controllers\Admin\RekeningAkuntanController;
use App\Http\Controllers\Admin\RekeningPendanaanController;
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
            'pengajuan-pinjaman' => PengajuanPinjamanController::class,
            'jadwal-pencairan' => JadwalPencairanController::class,
            'pinjaman-kelompok' => PinjamanKelompokController::class,
        ]);

        // Rekening Routes
        Route::prefix('/rekening-pendanaan')->name('rekening-pendanaan.')->group(function () {
            // Rekening Pendanaan
            Route::get('/', [RekeningPendanaanController::class, 'index'])->name('index');
        });
        Route::prefix('/rekening-akuntan')->name('rekening-akuntan.')->group(function () {
            // Rekening Akuntan
            Route::get('/', [RekeningAkuntanController::class, 'index'])->name('index');
            // Deposit
            Route::get('/deposit', [RekeningAkuntanController::class, 'deposit'])->name('deposit');
            Route::post('/submit-deposit', [RekeningAkuntanController::class, 'submit_deposit'])->name('submit-deposit');
            // Transfer
            Route::get('/transfer', [RekeningAkuntanController::class, 'transfer'])->name('transfer');
            Route::post('/submit-transfer', [RekeningAkuntanController::class, 'submit_transfer'])->name('submit-transfer');
        });

        // Settings routes
        Route::prefix("/settings")->name('settings.')->group(function () {
            // Index settings
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            // Actions settings
            Route::put('/account/save-changes', [SettingsController::class, 'account_save_changes'])->name('account.save-changes');
            Route::put('/pinjaman-settings/save-changes', [SettingsController::class, 'pinjaman_save_changes'])->name('pinjaman.save-changes');
            Route::put('/kelompok-settings/save-changes', [SettingsController::class, 'kelompok_save_changes'])->name('kelompok.save-changes');
        });

        // Exports routes
        // Laporan pengajuan pinjaman
        Route::get('/laporan/pengajuan-pinjaman/export', [PengajuanPinjamanController::class, 'export'])->name('pengajuan-pinjaman.export');
        // Laporan jadwal pencairan
        Route::get('/laporan/jadwal-pencairan/export', [JadwalPencairanController::class, 'export'])->name('jadwal-pencairan.export');
        // Laporan pinjaman kelompok
        Route::get('/laporan/pinjaman-kelompok/export', [PinjamanKelompokController::class, 'export'])->name('pinjaman-kelompok.export');
        // Laporan pinjaman kelompok
        Route::get('/laporan/transaksi-rekening/export', [RekeningAkuntanController::class, 'export'])->name('transaksi-rekening.export');
    });
});
