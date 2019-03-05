<?php

namespace Signifly\Janitor;

use Illuminate\Support\Facades\Auth;
use Signifly\Janitor\Contracts\Proxy;

abstract class AbstractProxy implements Proxy
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the guard
     *
     * @return string
     */
    protected function getGuard()
    {
        return Auth::guard();
    }

    /**
     * Get the user instance.
     *
     * @return object
     */
    protected function getUserInstance()
    {
        $userClass = config('auth.providers.users.model');
        return new $userClass;
    }

    /**
     * Get username field.
     *
     * @return string
     */
    protected function getUsernameField()
    {
        return 'email';
    }
}
