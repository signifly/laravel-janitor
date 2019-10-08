<?php

namespace Signifly\Janitor\Contracts;

interface Proxy
{
    /**
     * Attempt to log the user in by username and password.
     *
     * @param  mixed $username
     * @param  mixed $password
     * @return array
     */
    public function attemptLogin($username, $password): array;

    /**
     * Attempt refreshing the token.
     *
     * @param  string|null $refreshToken
     * @return array
     */
    public function attemptRefresh($refreshToken = null): array;

    /**
     * Attempt to log the user out.
     *
     * @return void
     */
    public function attemptLogout(): void;
}
