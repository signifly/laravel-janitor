<?php

namespace Signifly\Janitor\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Signifly\Janitor\Facades\Janitor;
use Signifly\Janitor\JWTProxy;
use Signifly\Janitor\PassportProxy;

class JanitorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_jwt_proxy()
    {
        $driver = Janitor::driver('jwt');

        $this->assertInstanceOf(JWTProxy::class, $driver);
    }

    /** @test */
    public function it_returns_a_passport_proxy()
    {
        $driver = Janitor::driver('passport');

        $this->assertInstanceOf(PassportProxy::class, $driver);
    }

    /** @test */
    public function it_register_routes()
    {
        $router = app('router');

        Janitor::routes();

        $this->assertEquals(5, $router->getRoutes()->count());
        $routeNames = [
            'janitor.login',
            'janitor.refresh',
            'janitor.logout',
            'janitor.password.email',
            'janitor.password.reset',
        ];
        collect($router->getRoutes()->getRoutes())->each(function ($route) use ($routeNames) {
            $this->assertContains($route->getName(), $routeNames);
        });
    }
}
