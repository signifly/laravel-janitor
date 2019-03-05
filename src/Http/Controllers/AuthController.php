<?php

namespace Signifly\Janitor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Signifly\Janitor\Contracts\Factory;

class AuthController
{
    protected $proxy;

    public function __construct(Factory $proxy)
    {
        $this->proxy = $proxy->driver(config('janitor.default'));
    }

    public function login(Request $request)
    {
        $data = $this->proxy->attemptLogin(
            $request->input(config('janitor.username_field')),
            $request->input('password')
        );

        return new JsonResponse($data);
    }

    public function logout()
    {
        return new JsonResponse(['message' => 'Successfully logged out.']);
    }

    public function refresh(Request $request)
    {
        $data = $this->proxy->attemptRefresh(
            $request->input('refresh_token')
        );

        return new JsonResponse($data);
    }
}
