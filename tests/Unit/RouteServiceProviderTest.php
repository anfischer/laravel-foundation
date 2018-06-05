<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Providers\RouteServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Mockery;
use Orchestra\Testbench\TestCase;

class RouteServiceProviderTest extends TestCase
{
    /** @test */
    public function it_loads_the_route_file_specified_for_the_service_and_sets_the_services_namespace()
    {
        $namespace = 'Application\Services\Web\Http\Controllers';
        $routesPath = __DIR__.'/../fixtures/routes.php';

        $application = Mockery::mock(Application::class);

        $router = Mockery::mock(Router::class);
        $router->shouldReceive('get')->once()->withArgs(['/test', 'TestController@testOne']);
        $router->shouldReceive('get')->once()->withArgs(['/test-two', 'TestController@testTwo'])->getMock();

        $router->shouldReceive('group')->once()->withArgs([['namespace' => $namespace], Mockery::on(
            function ($closure) use ($router) {
                $closure($router);
                return \is_callable($closure);
            }
        )]);

        $provider = new class($application) extends RouteServiceProvider {
            public function map(Router $router)
            {
            }
        };

        $provider->loadRoutesFile($router, $namespace, $routesPath);
    }
}
