# Reset your Laravel application to a previous state

This Laravel package lets you restore your application to a previous declared state. This is helpfull for demo applications where you set your users free but want to reset the state, for example, every midnight.

## How to install
First require it in composer:

```bash
composer require beeldvoerders/laravel-reset
```

Next, add the ResetServiceProvider class to your config/app.php providers array:


```php
Beeldvoerders\Reset\ResetServiceProvider::class
```

## How to use
You can specify which directories has to reset in the reset config file. By default this is the storage/app directory. If you want to change these config settings, publish them first to your application:

```bash
php artisan vendor:publish --provider="Beeldvoerders\Reset\ResetServiceProvider"
```

If you set the config right, you have to create the back-up on which the restore is based:

```bash
php artisan reset:create
```

Once the back-up is created, test it by making some changes to your application and reset the state:

```bash
php artisan reset
```

If you want to reset your application once every midnight, then you should add the Reset command to the Laravel scheduler:

```php
$schedule->command('reset')
      ->daily();
```

## Contact

If you discover a bug, wants to participate or just have a question, feel free to contact me at daan@beeldvoerders.nl.

## License

This package is open-sourced software licensed under the MIT license.
