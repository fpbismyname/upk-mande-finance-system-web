<?php

use App\Http\Controllers\Admin\User\Action\AddUser;
use App\Http\Controllers\Admin\User\Action\DeleteUser;
use App\Http\Controllers\Admin\User\Action\UpdateUser;
use App\Http\Controllers\Admin\User\CreateUser;
use App\Http\Controllers\Admin\User\EditUser;
use App\Http\Controllers\Admin\User\ListUser;
use App\Http\Controllers\Admin\User\ViewUser;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->name('users.')->group(function () {
    // View routes
    Route::get('/', ListUser::class)->name('index');
    Route::get('/create', CreateUser::class)->name('create');
    Route::get('/{id}/view', ViewUser::class)->name('view');
    Route::get('/{id}/edit', EditUser::class)->name('edit');
    // Action routes
    Route::post('/add', AddUser::class)->name('add');
    Route::put('/{id}/update', UpdateUser::class)->name('update');
    Route::delete('/{id}/delete', DeleteUser::class)->name('delete');
});