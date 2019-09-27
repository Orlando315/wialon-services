<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WialonProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        return [Wialon::class];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Wialon', function ($app) {
            return new Wialon($app->make('Wialon'));
        });
    }
}
