<?php

use App\Http\Controllers\Admin\Auth\LoginView;
use App\Http\Controllers\Client\App\Dashboard\DashboardView;
use App\Http\Controllers\Client\Auth\RegisterView;
use App\Http\Controllers\Client\HomeView;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->name('client.')->group(function(){
    /**
     * Landing page routes
     */
    Route::get('/', HomeView::class)->name('home');
    
    /**
     *  Auth routes
     */

    // Login page
    Route::get('/login', LoginView::class)->name('login');
    // Login page
    Route::get('/register', RegisterView::class)->name('register');

    /**
     *  Client app pages
     */
    Route::prefix('/app')->name('app')->group(function(){
        // Dashboard pages
        Route::get('/dashboard', DashboardView::class)->name('index');
    });
});