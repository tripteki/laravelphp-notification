<h1 align="center">Notification</h1>

This package provides is an implementation of notification in repository pattern for Lumen and Laravel.

Getting Started
---

Installation :

```
$ composer require tripteki/laravelphp-notification
```

How to use it :

- Put `Tripteki\Notification\Providers\NotificationServiceProvider` to service provider configuration list.

- Migrate.

```
$ php artisan migrate
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

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
