<?php

namespace Tripteki\Notification\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Helpers\ProjectHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "adminer:install:notification";

    /**
     * @var string
     */
    protected $description = "Install the notification stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        $this->installStack();

        return 0;
    }

    /**
     * @return int|null
     */
    protected function installStack()
    {
        (new Filesystem)->ensureDirectoryExists(base_path("routes/user"));
        (new Filesystem)->ensureDirectoryExists(base_path("routes/admin"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/user/notification.php", base_path("routes/user/notification.php"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/admin/notification.php", base_path("routes/admin/notification.php"));
        $this->helper->putRoute("api.php", "user/notification.php");
        $this->helper->putRoute("api.php", "admin/notification.php");

        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Notification"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Notification", app_path("Http/Controllers/Notification"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Notifications"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Notifications", app_path("Http/Requests/Notifications"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Admin/Notification"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Admin/Notification", app_path("Http/Controllers/Admin/Notification"));
        (new Filesystem)->ensureDirectoryExists(app_path("Imports/Notifications"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Imports/Notifications", app_path("Imports/Notifications"));
        (new Filesystem)->ensureDirectoryExists(app_path("Exports/Notifications"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Exports/Notifications", app_path("Exports/Notifications"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Admin/Notifications"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Admin/Notifications", app_path("Http/Requests/Admin/Notifications"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Responses"));

        $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Illuminate\Notifications\Notifiable::class);

        $this->info("Adminer Notification scaffolding installed successfully.");
    }
};
