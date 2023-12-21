<?php

namespace LaravelSuperBan\SuperBan\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelSuperBan\SuperBan\SuperBanServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LaravelSuperBan\\SuperBan\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-superban_table.php.stub';
        $migration->up();
        */
    }
}
