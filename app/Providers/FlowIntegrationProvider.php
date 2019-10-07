<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FlowIntegrationProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Flow', function ($app) {
            return new Flow($app->make('Flow'));
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        return [Flow::class];
    }
}
