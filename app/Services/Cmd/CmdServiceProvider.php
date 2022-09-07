<?php

namespace App\Services\Cmd;

use Carbon\Laravel\ServiceProvider;

class CmdServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/cmd.php', 'cmd');

        $this->app->singleton('cmd', function ($app) {
            return new CmdManager($app);
        });
    }
}