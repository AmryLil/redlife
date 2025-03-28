<?php

namespace App\Providers;

use App\Models\BloodStockDetail;
use App\Observers\BloodStockDetailObserver;
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
        // BloodStockDetail::observe(BloodStockDetailObserver::class);
    }
}
