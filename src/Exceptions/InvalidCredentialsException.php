<?php

namespace Signifly\Janitor\Exceptions;

use Illuminate\Auth\AuthenticationException;

class InvalidCredentialsException extends AuthenticationException
{
    /** @var string */
    private $username;

    public static function forUsername(string $username): self
    {
        $exception = new static(trans('auth.failed'));
        $exception->username = $username;

        return $exception;
    }

    public static function withDefaultMessage(): self
    {
        return new static(trans('auth.failed'));
    }

    protected function getUsername(): string
    {
        return $this->username;
    }
}
