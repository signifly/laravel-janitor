<?php

namespace Signifly\Janitor;

use Illuminate\Support\ServiceProvider;
use Signifly\Janitor\Contracts\Factory;
use Illuminate\Contracts\Support\DeferrableProvider;

class JanitorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/janitor.php' => config_path('janitor.php'),
            ], 'janitor-config');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/janitor.php', 'janitor');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new JanitorManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
    }
}
