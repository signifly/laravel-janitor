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
    public function attemptLogin($username, $password)
    {
        $credentials = [
            $this->getUsernameField() => $username,
            'password' => $password,
        ];

        event(new Attempting($this->getGuard(), $credentials, false));

        $user = $this->getUserInstance()
            ->where($this->getUsernameField(), $username)
            ->first();

        $token = Auth::attempt($credentials);

        if (is_null($user) || is_null($token)) {
            event(new Failed($this->getGuard(), $user, $credentials));
            throw InvalidCredentialsException::forUsername($username);
        }

        event(new Authenticated($this->getGuard(), $user));
        event(new Login($this->getGuard(), $user, false));

        return $this->prepareToken($token);
    }

    public function attemptRefresh($refreshToken = null)
    {
        $token = Auth::refresh();

        return $this->prepareToken($token);
    }

    public function attemptLogout()
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
