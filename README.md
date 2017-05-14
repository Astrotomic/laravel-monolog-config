# Monolog Configurator - Laravel 5

[![GitHub Author](https://img.shields.io/badge/author-@astrotomic-orange.svg?style=flat-square)](https://github.com/Astrotomic)
[![GitHub release](https://img.shields.io/github/release/astrotomic/laravel-monolog-config.svg?style=flat-square)](https://github.com/Astrotomic/laravel-monolog-config/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Astrotomic/laravel-monolog-config/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/Astrotomic/laravel-monolog-config.svg?style=flat-square)](https://github.com/Astrotomic/laravel-monolog-config/issues)


[![StyleCI](https://styleci.io/repos/67026923/shield)](https://styleci.io/repos/67026923)
[![Code Climate](https://img.shields.io/codeclimate/github/Astrotomic/laravel-monolog-config.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/laravel-monolog-config)
[![Code Climate](https://img.shields.io/codeclimate/issues/github/Astrotomic/laravel-monolog-config.svg?style=flat-square)](https://codeclimate.com/github/Astrotomic/laravel-monolog-config/issues)

This package provides a simple way to configure monolog in Laravel/Lumen.

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

**v1.0.0**
* `\Monolog\Handler\ErrorLogHandler`
* `\Monolog\Handler\GelfHandler`
* `\Monolog\Handler\LogglyHandler`
* `\Monolog\Handler\MandrillHandler`
* `\Monolog\Handler\MongoDBHandler`
* `\Monolog\Handler\NativeMailerHandler`
* `\Monolog\Handler\RotatingFileHandler`
* `\Monolog\Handler\StreamHandler`
* `\Monolog\Handler\SyslogHandler`

**v1.1.0**
* `\Monolog\Handler\HipChatHandler`
* `\Monolog\Handler\IFTTTHandler`
* `\Monolog\Handler\LogEntriesHandler`
* `\Monolog\Handler\NullHandler`
* `\Monolog\Handler\RedisHandler`
* `\Monolog\Handler\ZendMonitorHandler`

**v1.3.0**
* `\Monolog\Handler\SlackHandler`
* `\Monolog\Handler\SlackWebhookHandler`
* `\Monolog\Handler\SlackbotHandler`
