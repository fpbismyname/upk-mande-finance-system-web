<?php

use App\Http\Controllers\Admin\Auth\Action\SubmitLogin;
use App\Http\Controllers\Admin\Auth\Action\SubmitLogout;
use App\Http\Controllers\Admin\Auth\LoginView;
use App\Http\Controllers\Admin\Dashboard\DashboardView;
use App\Http\Middleware\Auth\Allowance;
use Illuminate\Support\Facades\Route;

/**
 *  Admin routes
 */
Route::prefix('/admin')->name('admin.')->group(function () {

    /**
     *  Login routes
     */
    Route::prefix('/login')->name('login.')->group(function () {
        // login view
        Route::get('/', LoginView::class)->name('index');
        // submit login action
        Route::post('/submit', SubmitLogin::class)->name('submit');
    });

    /**
     * Dashboard routes
     */
    Route::middleware([Allowance::class])->group(function () {
        // submit logout action 
        Route::post('/logout', SubmitLogout::class)->name('logout.submit');
        // Dashboard View
        Route::get('/', DashboardView::class)->name('index');
        // Users Routes
        include __DIR__ . "/users.php";
        // Kelompok + Anggota Routes
        include __DIR__ . "/kelompok.php";
        // Pendanaan Routes
        include __DIR__ . "/pendanaan.php";
        // Pengajuan pinjaman Routes
        include __DIR__ . "/pengajuan_pinjaman.php";
        // Jadwal pencairan Routes
        include __DIR__ . "/jadwal_pencairan.php";
    });
});