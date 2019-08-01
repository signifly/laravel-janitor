# Easily add login proxy to your Laravel API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/signifly/laravel-janitor.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-janitor)
[![Build Status](https://img.shields.io/travis/signifly/laravel-janitor/master.svg?style=flat-square)](https://travis-ci.org/signifly/laravel-janitor)
[![StyleCI](https://styleci.io/repos/173741214/shield?branch=master)](https://styleci.io/repos/173741214)
[![Quality Score](https://img.shields.io/scrutinizer/g/signifly/laravel-janitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/signifly/laravel-janitor)
[![Total Downloads](https://img.shields.io/packagist/dt/signifly/laravel-janitor.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-janitor)

The `signifly/laravel-janitor` package allows you to easily add a login proxy to your Laravel API.

You can find two articles that walk you through getting started using:
- [Passport Proxy](https://medium.com/@codingcave/api-authentication-with-laravel-janitor-part-1-laravel-passport-proxy-d1d1e05d687e)
- [JWT Proxy](https://medium.com/@codingcave/api-authentication-with-laravel-janitor-part-2-laravel-jwt-proxy-d303afe8eba9)

## Documentation
To get started you have to either install `laravel/passport` or `tymon/jwt-auth`. Please refer to their documentation for how to configure those packages.

*NOTE: For now the supported versions for `tymon/jwt-auth` is `1.0.0-rc.*`.*

## Installation

You can install the package via composer:

```bash
composer require signifly/laravel-janitor
```

The package will automatically register itself.


You can optionally publish the config file with:

```bash
php artisan vendor:publish --tag="janitor-config"
```

After pulling in the package and (optionally) publishing the config, then add the routes to your `routes/api.php` file:

```php
Janitor::routes();
```

It will by default add routes for the following:
- login by username and password (/login)
- refresh current user access token (/login/refresh)
- log the user out (/logout)
- send password reset email (/password/email)
- reset password (/password/reset)

You can also define a specific set of routes by passing a Closure:

```php
Janitor::routes(function ($router) {
    // Login and logout routes
    $router->forAuthentication();

    // Password reset routes
    $router->forPasswordReset();
});
```

Finally, add `JANITOR_DRIVER=driver-name` to your .env file.

The supported drivers are: `passport` and `jwt`.

*NOTE: It does not support a default driver and throws an `InvalidArgumentException` if omitted.*

### Resetting passwords

In order to use the reset password implementation in an API, you have to add a custom reset password notification to your user model.

```php
// App\User.php

/**
 * Send the password reset notification.
 *
 * @param  string  $token
 * @return void
 */
public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}
```

The notification should format a correct link to your client app's reset password url.

## Testing
```bash
composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
