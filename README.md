# Monolog Configurator - Laravel 5

[![GitHub release](https://img.shields.io/github/release/fenos/Notifynder.svg?style=flat-square)](https://github.com/fenos/Notifynder/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/fenos/Notifynder/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/fenos/Notifynder.svg?style=flat-square)](https://github.com/fenos/Notifynder/issues)
[![StyleCI](https://styleci.io/repos/18425539/shield)](https://styleci.io/repos/18425539)

This package provides a simple way to configure monolog in laravel.

-----

## Installation

### Step 1

Add it on your `composer.json`

```
"gummibeer/laravel-monolog-config": "^1.0"
```

and run

```
composer update
```

or run

```
composer require gummibeer/laravel-monolog-config
```

### Step 2

Add the following string to `config/app.php`

**Providers array:**

```
\Gummibeer\MonologConfig\MonologConfigServiceProvider::class,
```

### Step 3

Publish the configuration for monolog with the following command:

```
php artisan vendor:publish --provider="Gummibeer\MonologConfig\MonologConfigServiceProvider"
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
    $configurator = new \Gummibeer\MonologConfig\MonologConfigurator($monolog);
    $configurator->run();
});
```

You can configure monolog after this your own in this method the normal [Laravel way](https://laravel.com/docs/5.2/errors#configuration).

## Handlers

At the moment this class supports the following handlers, if you need any other one just create a PR or write an issue.

* `\Monolog\Handler\ErrorLogHandler`
* `\Monolog\Handler\GelfHandler`
* `\Monolog\Handler\LogglyHandler`
* `\Monolog\Handler\MandrillHandler`
* `\Monolog\Handler\MongoDBHandler`
* `\Monolog\Handler\NativeMailerHandler`
* `\Monolog\Handler\RotatingFileHandler`
* `\Monolog\Handler\StreamHandler`
* `\Monolog\Handler\SyslogHandler`