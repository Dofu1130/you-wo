<?php

namespace App\Providers;

use App\Services\CurrencyExchangeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(CurrencyExchangeService::class, function ($app) {
        //     return new CurrencyExchangeService(config('currencies.currencies'));
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
