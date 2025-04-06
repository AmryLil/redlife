<?php

namespace App\Providers;

use App\Models\BloodStockDetail;
use App\Observers\BloodStockDetailObserver;
use App\Policies\PermissionPolicy;
use App\View\Components\Icons\BloodDrop;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

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
