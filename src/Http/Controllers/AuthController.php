<?php

namespace Signifly\Janitor\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Signifly\Janitor\Contracts\Factory;

class AuthController extends Controller
{
    protected $proxy;

    public function __construct(Factory $proxy)
    {
        $this->proxy = $proxy->driver(config('janitor.default'));
    }

    public function login(Request $request)
    {
        $usernameField = $this->proxy->getUsernameField();

        $request->validate([
            $usernameField => 'required',
            'password' => 'required',
        ]);

        $data = $this->proxy->attemptLogin(
            $request->input($usernameField),
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
