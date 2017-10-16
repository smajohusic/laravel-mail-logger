# Laravel Mail Logger

### Introduction
Mail logger has one purpose, save mail before they are sent from your app. This enables you to have backup of mail and a overview if something goes wrong.

This package listens to the MessageSending event fired from Mailer. The listener will then save the needed form information, event, route and recipient to the database.

This package also supports auto deleting. You can define how long the the app should keep the logs by defining days in the config file.

### Requirements
Queue driver. Because every database action is queued as a job.

### Installation

1. ```composer require smajo/laravel-mail-logger```
2. Add ```Smajo\MailLogger\MailLogServiceProvider::class``` to providers in app.php
3. Execute command: ```php artisan vendor:publish --provider="Smajo\MailLogger\MailLogServiceProvider"```
4. Run: ```php artisan migrate``` to generate the mail-logger table

### Usage

##### mailLogger.php config

Define your field input names that's used in form for user E-mail:

```php
'toEmailAddresses' => [
    'email',
    'e-mail',
    'to'
]
``` 
