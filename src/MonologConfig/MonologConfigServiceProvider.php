<?php

namespace Astrotomic\MonologConfig;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

class MonologConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->config();
    }

    protected function config()
    {
        $source = realpath(__DIR__.'/../config/monolog.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('monolog.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('monolog');
        }

        $this->mergeConfigFrom($source, 'monolog');
    }
}
