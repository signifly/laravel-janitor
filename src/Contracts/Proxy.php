<?php

namespace Signifly\Janitor\Contracts;

interface Proxy
{
    public function attemptLogin($username, $password);

    public function attemptRefresh($refreshToken = null);

    public function attemptLogout();
}
