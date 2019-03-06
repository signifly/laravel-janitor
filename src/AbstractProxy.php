<?php

namespace Signifly\Janitor;

use Illuminate\Support\Facades\Auth;
use Signifly\Janitor\Contracts\Proxy;

abstract class AbstractProxy implements Proxy
{
    /** @var array */
    protected $config;

    /** @var string */
    protected $usernameField;

    /**
     * Create a new instance.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the guard.
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
     * @return \Illuminate\Contracts\Auth\Authenticatable
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
    public function getUsernameField(): string
    {
        return $this->usernameField ?? config('janitor.username_field');
    }

    /**
     * Set the username field.
     *
     * @param string $username
     * @return $this
     */
    public function setUsernameField(string $username): self
    {
        $this->usernameField = $username;

        return $this;
    }
}
