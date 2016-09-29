<?php

namespace Astrotomic\MonologConfig;

use Illuminate\Support\ServiceProvider;

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
        $this->publishes([
            __DIR__.'/../config/monolog.php' => config_path('monolog.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/monolog.php', 'monolog');
    }
}
