<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Contracts\ProductRepositoryInterface::class, \App\Repositories\ProductRepository::class);
        $this->app->bind(\App\Repositories\Contracts\ManufacturerRepositoryInterface::class, \App\Repositories\ManufacturerRepository::class);
        $this->app->bind(\App\Repositories\Contracts\ProductTypeRepositoryInterface::class, \App\Repositories\ProductTypeRepository::class);
        $this->app->bind(\App\Repositories\Contracts\ServiceRepositoryInterface::class, \App\Repositories\ServiceRepository::class);
        $this->app->bind(\App\Repositories\Contracts\CurrencyRateRepositoryInterface::class, \App\Repositories\CurrencyRateRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
