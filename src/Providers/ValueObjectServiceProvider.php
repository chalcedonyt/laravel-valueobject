<?php

namespace Chalcedonyt\ValueObject\Providers;

use Illuminate\Support\ServiceProvider;
use Chalcedonyt\ValueObject\Commands\ValueObjectGenerator;

class ValueObjectServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'valueobject');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['command.valueobject.generate'] = $this->app->share(
            function ($app) {
                return new ValueObjectGenerator($app['view'], $app['files']);
            }
        );
        $this->commands('command.valueobject.generate');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['valueobject', 'command.valueobject.generate'];
    }
}
