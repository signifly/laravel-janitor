<?php

namespace Signifly\Janitor\Facades;

use Signifly\Janitor\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Facade;
use Signifly\Janitor\Contracts\Factory;

/**
 * @see \Signifly\Janitor\JanitorManager
 */
class Janitor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return Factory::class;
    }

    /**
     * Binds the Janitor routes into the controller.
     *
     * @param  callable|null  $callback
     * @param  array  $options
     * @return void
     */
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->all();
        };

        $defaultOptions = [
            'namespace' => '\Signifly\Janitor\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}
