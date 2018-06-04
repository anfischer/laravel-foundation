<?php
namespace Anfischer\Foundation\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->register(ApiServiceProvider::class);
        $this->app->register(LandingWebServiceProvider::class);
        $this->app->register(TrainerWebServiceProvider::class);
        $this->app->register(ClientWebServiceProvider::class);
    }
}
