# Disposable / Temporary Email Address Filter

This package provides a method for determining whether an email address is a disposable / temporary email address.

All credit to the maintaining of the list of disposable / temporary email addresses goes to [github.com/disposable-email-domains/disposable-email-domains](https://github.com/disposable-email-domains/disposable-email-domains).

## Installation

To install via [Composer](https://getcomposer.org/download/):

```bash
composer require martynasbakanas/php-disposable-emails
```

## Usage in Laravel

#### AppServiceProvider.php
```php
<?php

...
use MartynasBakanas\PHPDisposableEmails\EmailCheck;

public function register(): void
{
    ...

    $this->app->singleton('email-check', function () {
        return new EmailCheck();
    });
}

public function boot(): void
{
    ...

    Validator::extend('not-disposable', function ($attribute, $value, $parameters) {
        return app('email-check')->isValid($value);
    });
}
```

#### YourController.php
```php
public function store(Request $request)
{
    $request->validate([
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users',
            'not-disposable'
        ],
    ];

    ...
}

```