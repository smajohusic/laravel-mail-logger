<?php

namespace Smajo\MailLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Events\Dispatcher;
use Illuminate\Console\Scheduling\Schedule;
use Smajo\MailLogger\Jobs\StoreMailEventToDatabase;
use Smajo\MailLogger\Commands\DeleteLogs;

class MailLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * Publish  config
         */
        $this->publishes([__DIR__ . '/../publishes/config/mailLogger.php' => config_path('mailLogger.php')]);

        /*
         * Load migration
         */
        $this->loadMigrationsFrom(__DIR__ . '/../src/migrations');

        /*
         * Register a listener on MessageSending event, and dispatch a job to save the information to the logs
         */
        app(Dispatcher::class)->listen('Illuminate\Mail\Events\MessageSending', function ($event) {
            dispatch(new StoreMailEventToDatabase($event, request()->except('_token', 'method')));
        });

        if (config('mailLogger.enableAutoDeletion')) {
            $this->app->booted(function () {
                $schedule = app(Schedule::class);
                $schedule->command('delete:logs')->daily();
            });
        }
    }

    public function register()
    {
        /*
         * Register the command
         */
        $this->commands([
            DeleteLogs::class
        ]);
    }
}
