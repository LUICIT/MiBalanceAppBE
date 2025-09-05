<?php

namespace App\Providers;

use App\Interfaces\PeriodRepositoryInterface;
use App\Repositories\PeriodRepository;
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
