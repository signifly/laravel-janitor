<?php

namespace Signifly\Janitor\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidClientCredentialsException extends HttpException
{
    public static function withDefaultMessage(): self
    {
        return new static(422, trans('auth.failed'));
    }
}
