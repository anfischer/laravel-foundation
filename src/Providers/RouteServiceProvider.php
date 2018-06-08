<?php

namespace Anfischer\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

abstract class RouteServiceProvider extends BaseServiceProvider
{
    /**
     * Abstract function to set Service controller namespace and load routes
     *
     * @param \Illuminate\Routing\Router $router
     */
    abstract public function map(Router $router);

    public function loadRoutesFile($router, $namespace, $path) : void
    {
        $router->group(['namespace' => $namespace], function ($router) use ($path) {
            if (file_exists($path)) {
                require $path;
            }
        });
    }
}
