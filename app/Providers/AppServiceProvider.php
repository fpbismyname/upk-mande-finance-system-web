<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Display All App Name to all routes
        View::composer('*', function ($view) {
            $app_name = config('app.name', 'Laravel');
            $app_payload = [
                'name' => $app_name,
            ];
            $view->with('app', $app_payload);
        });

        // Set Default paginator theme
        Paginator::defaultView('components.ui.daisyui');

        // Set Timezone
        date_default_timezone_set(config('app.timezone'));

        /**
         * Gate for limiting permission access to some actions
         */
        // Admin gate permission
        Gate::define('manage-users', fn($user) => in_array($user->role->value, ['admin']));
        Gate::define('manage-kelompok', fn($user) => in_array($user->role->value, ['admin']));
        Gate::define('manage-rekening', fn($user) => in_array($user->role->value, ['akuntan', 'pengelola_dana']));
        Gate::define('manage-pengajuan-pinjaman', fn($user) => in_array($user->role->value, ['kepala_institusi']));
        Gate::define('manage-jadwal-pencairan', fn($user) => in_array($user->role->value, ['pengelola_dana']));
        Gate::define('manage-pinjaman-kelompok', fn($user) => in_array($user->role->value, ['akuntan']));
        Gate::define('manage-cicilan-kelompok', fn($user) => in_array($user->role->value, ['akuntan']));
        Gate::define('print-data', fn($user) => in_array($user->role->value, ['akuntan']));
        Gate::define('manage-financial-settings', fn($user) => in_array($user->role->value, ['akuntan']));
        Gate::define('has-bank-account-number', fn($user) => in_array($user->role->value, ['akuntan']));
    }
}
