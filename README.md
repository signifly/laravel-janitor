# Easily add login proxy to your Laravel API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/signifly/laravel-janitor.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-janitor)
[![Build Status](https://img.shields.io/travis/signifly/laravel-janitor/master.svg?style=flat-square)](https://travis-ci.org/signifly/laravel-janitor)
[![StyleCI](https://styleci.io/repos/117567334/shield?branch=master)](https://styleci.io/repos/117567334)
[![Quality Score](https://img.shields.io/scrutinizer/g/signifly/laravel-janitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/signifly/laravel-janitor)
[![Total Downloads](https://img.shields.io/packagist/dt/signifly/laravel-janitor.svg?style=flat-square)](https://packagist.org/packages/signifly/laravel-janitor)

The `signifly/laravel-janitor` package allows you to easily add a login proxy to your Laravel API.

## Documentation
To get started you have to either install `laravel/passport` or `tymon/jwt-auth`. Please refer to their documentation for how to configure those packages.

*NOTE: The current support for `tymon/jwt-auth` is limited to `^1.0`.*

## Installation

You can install the package via composer:

```bash
$ composer require signifly/laravel-janitor
```

The package will automatically register itself.


You can optionally publish the config file with:

```bash
$ php artisan vendor:publish --tag="janitor-config"
```

After pulling in the package and (optionally) publishing the config, then add the routes to your `routes/api.php` file:

```php
Janitor::routes();
```

It will by default add routes for the following:
- login by username and password (/login)
- refresh current user access token (/login/refresh)
- log the user out (/logout)

Finally, add `JANITOR_DRIVER=driver-name` to your .env file. 

*NOTE: It does not support a default driver and throws an `InvalidArgumentException` if omitted.*

## Testing
```bash
$ composer test
```

## Security

If you discover any security issues, please email dev@signifly.com instead of using the issue tracker.

## Credits

- [Morten Poul Jensen](https://github.com/pactode)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
