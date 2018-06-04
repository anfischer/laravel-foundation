<?php

namespace Anfischer\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseServiceProvider;

abstract class RouteServiceProvider extends BaseServiceProvider
{
    /**
     * Read the routes from the "routes.php" file of this Service
     *
     * @param \Illuminate\Routing\Router $router
     */
    abstract public function map(Router $router);

    public function loadRoutesFile($router, $namespace, $path) : void
    {
        $router->group(['namespace' => $namespace], function () use ($path) {
            if (file_exists($path)) {
                require $path;
            }
        });
    }
}
