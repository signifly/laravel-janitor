<?php

namespace Signifly\Janitor;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all()
    {
        $this->forAuthentication();
        $this->forPasswordReset();
    }

    public function forAuthentication()
    {
        $this->router->post('/login', 'AuthController@login')
            ->name('janitor.login');

        $this->router->post('/login/refresh', 'AuthController@refresh')
            ->name('janitor.refresh');

        $this->router->group(['middleware' => ['auth'], function ($router) {
            $router->post('/logout', 'AuthController@logout')
                ->name('janitor.logout');
        }]);
    }

    public function forPasswordReset()
    {

    }
}
