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
        $this->router->post('login', 'AuthController@login')
            ->name('login');

        $this->router->post('login/refresh', 'AuthController@refresh')
            ->name('refresh');

        $this->router->group(['middleware' => ['auth:api']], function ($router) {
            $router->post('logout', 'AuthController@logout')
                ->name('logout');
        });
    }

    public function forPasswordReset()
    {
        $this->router->group(['middleware' => ['auth:api']], function ($router) {
            $router->post('password/email', 'ResetPasswordController@sendResetLinkEmail')
                ->name('password.email');

            $router->post('password/reset', 'ResetPasswordController@reset')
                ->name('password.reset');
        });
    }
}
