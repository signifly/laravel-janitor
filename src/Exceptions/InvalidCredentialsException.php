<?php

namespace Signifly\Janitor\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidCredentialsException extends HttpException
{
    /** @var string */
    private $username;

    public static function forUsername(string $username): self
    {
        $exception = new static(422, trans('auth.failed'));
        $exception->username = $username;

        return $exception;
    }

    public static function withDefaultMessage(): self
    {
        return new static(422, trans('auth.failed'));
    }

    protected function getUsername(): string
    {
        return $this->username;
    }
}
