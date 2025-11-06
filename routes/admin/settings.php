<?php

use App\Http\Controllers\Admin\Settings\Action\SaveChangesSettings;
use App\Http\Controllers\Admin\Settings\ViewSettings;
use Illuminate\Support\Facades\Route;

Route::prefix("/settings")->name('settings.')->group(function () {
    // View routes
    Route::get('/', ViewSettings::class)->name('view');

    // Action routes
    Route::put('/save-changes', SaveChangesSettings::class)->name('save-changes');
});