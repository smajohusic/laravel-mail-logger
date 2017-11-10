# Laravel Mail Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smajohusic/laravel-mail-logger.svg?style=flat-square)](https://packagist.org/packages/smajohusic/laravel-mail-logger)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/smajohusic/laravel-mail-logger/master.svg?style=flat-square)](https://travis-ci.org/smajohusic/laravel-mail-logger)
[![Total Downloads](https://img.shields.io/packagist/dt/smajohusic/laravel-mail-logger.svg?style=flat-square)](https://packagist.org/packages/smajohusic/laravel-mail-logger)

### Introduction
Mail logger has one purpose, save mail before they are sent from your app. This enables you to have backup of mail and a overview if something goes wrong.

This package listens to the MessageSending event fired from Mailer. The listener will dispatch a job, then save the needed form information, event, route and recipient to the database.

This package also supports auto deleting. You can define how long the the app should keep the logs by defining days in the config file.

### Installation

#### Laravel 5.5 +
1. ``composer require smajohusic/laravel-mail-logger``

2. Package is automatically discovered and registered via Laravel's automatic service provider registration.

#### Laravel 5.4 or earlier
1. ``composer require smajohusic/laravel-mail-logger``
2. Add ``Smajo\MailLogger\MailLogServiceProvider::class`` to providers in app.php
3. Execute command: ``php artisan vendor:publish --provider="Smajo\MailLogger\MailLogServiceProvider"``
4. Run: ``php artisan migrate`` to generate the mail-logger table

### Requirements
***Cron job***

To enable auto-deleting you will need to set up a cron job that runs 

```php artisan schedule:run```

### Usage

##### mailLogger.php config

By default the auto-deleting is disabled. To enable it set

```'enableAutoDeletion' => true,```

You can set how long the logs should be stored in the database by giving amount in days.

```'keepLogsForDays' => 30,``` 

To make it easier to find mails, you can define all "to" fields your app uses in forms. This will then find the 
user e-mail in the request, and save it as the "sender" field.

```php
'toEmailAddresses' => [
    'email',
    'e-mail',
    'to',
]
``` 
