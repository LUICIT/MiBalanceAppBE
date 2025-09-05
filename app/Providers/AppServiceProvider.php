<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\Concept;
use App\Models\Movement;
use App\Models\Period;
use App\Models\User;
use App\Observers\VersionedObserver;
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
        User::observe(VersionedObserver::class);
        Period::observe(VersionedObserver::class);
        Concept::observe(VersionedObserver::class);
        Movement::observe(VersionedObserver::class);
        Bill::observe(VersionedObserver::class);
    }
}
