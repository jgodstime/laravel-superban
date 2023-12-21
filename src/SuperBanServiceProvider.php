<?php

namespace LaravelSuperBan\SuperBan;

use Spatie\LaravelPackageTools\Package;
use LaravelSuperBan\SuperBan\Commands\SuperBanCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use LaravelSuperBan\SuperBan\Middlewares\SuperbanMiddleware;
use LaravelSuperBan\SuperBan\Exceptions\SuperbanInvalidArgumentException;

class SuperBanServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $this->app->singleton('superban.limiter', function ($app) {
            $cacheDriver = config('superban.cache_driver');
            switch ($cacheDriver) {
                case 'redis':
                    return new SuperBan($app['cache']);
                case 'database':
                    return new SuperBan($app['db']);
                default:
                    throw new SuperbanInvalidArgumentException("Invalid cache driver '$cacheDriver' for superban.");
            }
        });

        $this->app->singleton('superban.middleware', function ($app) {
            return new SuperbanMiddleware($app['superban.limiter']);
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-superban')
            ->hasConfigFile('superban')
            ->hasViews()
            ->hasMigration('create_superban_table')
            ->hasCommand(SuperBanCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    // ->publishAssets()
                    ->publishMigrations()
                    ->copyAndRegisterServiceProviderInApp();
                    // ->askToStarRepoOnGitHub();
            });
    }
}
