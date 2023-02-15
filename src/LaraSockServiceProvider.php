<?php

namespace HiFolks\LaraSock;

use HiFolks\LaraSock\Commands\LaraSockCommand;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class LaraSockServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $engine = match (true) {
            extension_loaded('swoole') => '<fg=green;options=bold>Swoole</>',
            extension_loaded('openswoole') => '<fg=green;options=bold>Open Swoole</>',
            default => '<fg=red;options=bold>No extension</>'
        };

        AboutCommand::add('LaraSock',
            fn () => [
                'Version' => \Composer\InstalledVersions::getPrettyVersion('hi-folks/lara-sock'),
                'WebSocket engine' => $engine,
            ]);

        $this->publishes([
            __DIR__.'/../config/lara-socks.php' => config_path('lara-socks.php'),
        ]);
        //$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                LaraSockCommand::class,
                //NetworkCommand::class,
            ]);
        }

        //$this->app->bind('lara-sock', function ($app) {
        //    return new \HiFolks\LaraSock\Facades\LaraSock();
        //});
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/lara-sock.php', 'lara-socks'
        );
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lara-sock')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lara-sock_table')
            ->hasCommand(LaraSockCommand::class);
    }
}
