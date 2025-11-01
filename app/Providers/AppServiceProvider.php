<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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

    }
}
