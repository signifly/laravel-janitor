<?php

namespace Signifly\Janitor;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Signifly\Janitor\Exceptions\InvalidCredentialsException;

class JWTProxy extends AbstractProxy
{
    public function attemptLogin($username, $password): array
    {
        $credentials = [
            $this->getUsernameField() => $username,
            'password' => $password,
        ];

        event(new Attempting($this->getGuard(), $credentials, false));

        $user = $this->getUserProvider()
            ->retrieveByCredentials($credentials);

        $token = Auth::attempt($credentials);

        if (is_null($user) || ! $token) {
            event(new Failed($this->getGuard(), $user, $credentials));
            throw InvalidCredentialsException::forUsername($username);
        }

        event(new Authenticated($this->getGuard(), $user));
        event(new Login($this->getGuard(), $user, false));

        return $this->prepareToken($token);
    }

    public function attemptRefresh($refreshToken = null): array
    {
        $token = Auth::refresh();

        return $this->prepareToken($token);
    }

    public function attemptLogout(): void
    {
        $user = Auth::user();

        Auth::logout();

        event(new Logout($this->getGuard(), $user));
    }

    protected function prepareToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
