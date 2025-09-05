<?php

namespace App\Providers;

use App\Repositories\Contracts\PeriodRepositoryInterface;
use App\Repositories\Eloquent\PeriodRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PeriodRepositoryInterface::class,
            PeriodRepository::class
        );
    }
}
