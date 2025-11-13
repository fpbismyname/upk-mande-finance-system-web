<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Scheduler
 */
Schedule::command('jadwal-pencairan:check')->everyFiveSeconds();
Schedule::command('pinjaman-kelompok:check')->everyFiveSeconds();
Schedule::command('limit-pinjaman-kelompok:check')->everyFiveSeconds();