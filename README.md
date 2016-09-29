# Monolog Configurator - Laravel 5

[![GitHub release](https://img.shields.io/github/release/Astrotomic/laravel-monolog-config.svg?style=flat-square)](https://github.com/Astrotomic/laravel-monolog-config/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Astrotomic/laravel-monolog-config/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/Astrotomic/laravel-monolog-config.svg?style=flat-square)](https://github.com/Astrotomic/laravel-monolog-config/issues)
[![StyleCI](https://styleci.io/repos/67026923/shield)](https://styleci.io/repos/67026923)

This package provides a simple way to configure monolog in laravel.

-----

## Installation

### Step 1

Add it on your `composer.json`

```
"astrotomic/laravel-monolog-config": "^1.0"
```

and run

```
composer update
```

or run

```
composer require astrotomic/laravel-monolog-config
```

### Step 2

Add the following string to `config/app.php`

**Providers array:**

```
\Astrotomic\MonologConfig\MonologConfigServiceProvider::class,
```

### Step 3

Publish the configuration for monolog with the following command:

```
php artisan vendor:publish --provider="Astrotomic\MonologConfig\MonologConfigServiceProvider"
```

And adjust all the configurations to your needs.

### Step 4

Use it as your monolog configuration tool. Add this to your `bootstrap/app.php` after the Interface bindings and before the return:

```php
/*
|--------------------------------------------------------------------------
| Configure Monolog
|--------------------------------------------------------------------------
*/

$app->configureMonologUsing(function (Monolog\Logger $monolog) {
    $configurator = new \Astrotomic\MonologConfig\MonologConfigurator($monolog);
    $configurator->run();
});
```

You can configure monolog after this your own in this method the normal [Laravel way](https://laravel.com/docs/5.2/errors#configuration).

## Handlers

At the moment this class supports the following handlers, if you need any other one just create a PR or write an issue.

* `\Monolog\Handler\ErrorLogHandler` - v1.0
* `\Monolog\Handler\GelfHandler` - v1.0
* `\Monolog\Handler\LogglyHandler` - v1.0
* `\Monolog\Handler\MandrillHandler` - v1.0
* `\Monolog\Handler\MongoDBHandler` - v1.0
* `\Monolog\Handler\NativeMailerHandler` - v1.0
* `\Monolog\Handler\RotatingFileHandler` - v1.0
* `\Monolog\Handler\StreamHandler` - v1.0
* `\Monolog\Handler\SyslogHandler` - v1.0
* `\Monolog\Handler\HipChatHandler` - v1.1
* `\Monolog\Handler\IFTTTHandler` - v1.1
* `\Monolog\Handler\LogEntriesHandler` - v1.1
* `\Monolog\Handler\NullHandler` - v1.1
* `\Monolog\Handler\RedisHandler` - v1.1
* `\Monolog\Handler\ZendMonitorHandler` - v1.1