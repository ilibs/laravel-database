<?php
namespace Very\Database;

use Illuminate\Support\ServiceProvider;
use Very\Database\Connectors\ConnectionFactory;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // Add a mysql extension to the original database manager
        $this->app['db']->extend('mysql', function ($config, $name) {
            return $this->app['db.factory']->make($config, $name);
        });
    }
}
