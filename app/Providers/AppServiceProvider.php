<?php

namespace App\Providers;

use App\Models\Rider;
use App\Models\Stock;
use App\Models\User;
use App\Policies\StockPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Stock::class, StockPolicy::class);

        Gate::define('access-admin', fn (User|Rider $actor): bool => $actor instanceof User);
        Gate::define('access-rider', fn (User|Rider $actor): bool => $actor instanceof Rider);
    }
}
