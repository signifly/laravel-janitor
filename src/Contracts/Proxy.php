<?php

namespace Signifly\Janitor\Contracts;

interface Proxy
{
    public function attemptLogin($username, $password): array;

    public function attemptRefresh($refreshToken = null): array;

    public function attemptLogout(): void;
}
