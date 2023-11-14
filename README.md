<h1 align="center">Notification</h1>

This package provides implementation of notification in repository pattern for Lumen and Laravel besides REST API starterpack of admin management with no intervention to codebase and keep clean.

Getting Started
---

Installation :

```
composer require tripteki/laravelphp-notification
```

How to use it :

- Put `Tripteki\Notification\Providers\NotificationServiceProvider` to service provider configuration list.

- Put `Tripteki\Notification\Providers\NotificationServiceProvider::ignoreMigrations()` into `register` provider, then publish migrations file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-notification-migrations
```

- Migrate.

```
php artisan migrate
```

- Publish tests file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-notification-tests
```

- Sample :

```php
use Tripteki\Notification\Contracts\Repository\Admin\INotificationRepository as INotificationAdminRepository;
use Tripteki\Notification\Contracts\Repository\INotificationRepository;

$notificationAdminRepository = app(INotificationAdminRepository::class);

// $notificationAdminRepository->get("..."); //
// $notificationAdminRepository->all(); //

$repository = app(INotificationRepository::class);
// $repository->setUser(...); //
// $repository->getUser(); //

// $repository->markAsRead("..."); //
// $repository->clear("..."); //
// $repository->all(); //
```

- Generate swagger files into your project's directory with putting this into your annotation configuration (optionally) :

```
base_path("app/Http/Controllers/Notification")
```

```
base_path("app/Http/Controllers/Admin/Notification")
```

Usage
---

`php artisan adminer:install:notification`

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
