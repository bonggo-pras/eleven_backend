<?php

namespace Webkul\Midtrans\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MidtransServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        
        $this->registerRoutes();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php',
            'paymentmethods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    public function registerRoutes()
    {
        Route::namespace('Webkul\Midtrans\Http\Controllers\API')
            ->prefix('api/v1/midtrans')
            ->group(__DIR__ . '/../../routes/api.php');
    }
}
