<?php

namespace Tripteki\Notification\Providers;

use Tripteki\Notification\Console\Commands\InstallCommand;
use Tripteki\Repository\Providers\RepositoryServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $repositories =
    [
        \Tripteki\Notification\Contracts\Repository\INotificationRepository::class => \Tripteki\Notification\Repositories\Eloquent\NotificationRepository::class,
        \Tripteki\Notification\Contracts\Repository\Admin\INotificationRepository::class => \Tripteki\Notification\Repositories\Eloquent\Admin\NotificationRepository::class,
    ];

    /**
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * @return bool
     */
    public static function shouldRunMigrations()
    {
        return static::$runsMigrations;
    }

    /**
     * @return void
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;
    }

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(function (\Illuminate\Notifications\Events\NotificationSent $notif) {

            if ($notif->channel === "database" && method_exists($notif->notification, "broadcastType")) {

                $notif->response->type = $notif->notification->broadcastType();
                $notif->response->save();
            }
        });

        $this->registerPublishers();
        $this->registerCommands();
        $this->registerMigrations();
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->isProduction() && $this->app->runningInConsole()) {

            $this->commands(
            [
                InstallCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && static::shouldRunMigrations()) {

            $this->loadMigrationsFrom(__DIR__."/../../database/migrations");
        }
    }

    /**
     * @return void
     */
    protected function registerPublishers()
    {
        if (! static::shouldRunMigrations()) {

            $this->publishes(
            [
                __DIR__."/../../database/migrations" => database_path("migrations"),
            ],

            "tripteki-laravelphp-notification-migrations");
        }

        $this->publishes(
        [
            __DIR__."/../../stubs/tests/Feature/Notification/NotificationTest.stub" => base_path("tests/Feature/Notification/NotificationTest.php"),
        ],

        "tripteki-laravelphp-notification-tests");
    }
};
