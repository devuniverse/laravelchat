<?php

namespace Devuniverse\Laravelchat;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\ServiceProvider;

class LaravelMessengerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/messenger.php' => config_path('messenger.php'),
            __DIR__.'/assets' => public_path('vendor/messenger'),
            __DIR__.'/views' => resource_path('views/vendor/messenger'),
        ]);

        // routes.
       $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // migrations.
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        view()->composer('*', function ($view){

         $view->with('chatPrefix', \Request()->lang.'/x' );

        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // register our controller
        $this->app->make('Devuniverse\Laravelchat\Controllers\MessageController');
        // Messenger Facede.
        $this->app->singleton('messenger', function () {
            return new Messenger;
        });
        $this->loadViewsFrom(__DIR__.'/views', 'messenger');

        $this->mergeConfigFrom(
            __DIR__.'/config/messenger.php', 'messenger'
        );

    }
}
