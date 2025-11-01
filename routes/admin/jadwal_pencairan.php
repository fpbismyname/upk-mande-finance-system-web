<?php

use App\Http\Controllers\Admin\JadwalPencairan\Action\MakeScheduleJadwalPencairan;
use App\Http\Controllers\Admin\JadwalPencairan\Action\RescheduleJadwalPencairan;
use App\Http\Controllers\Admin\JadwalPencairan\ListJadwalPencairan;
use App\Http\Controllers\Admin\JadwalPencairan\ViewJadwalPencairan;
use Illuminate\Support\Facades\Route;

Route::prefix("/jadwal-pencairan")->name('jadwal-pencairan.')->group(function () {
    // View routes
    Route::get("/", ListJadwalPencairan::class)->name('index');
    Route::get("/{id}/view", ViewJadwalPencairan::class)->name('view');
    // Action routes
    Route::get("/{id}/make-schedule", MakeScheduleJadwalPencairan::class)->name('create-schedule');
    Route::get("/{id}/reschedule", RescheduleJadwalPencairan::class)->name('reschedule');
});