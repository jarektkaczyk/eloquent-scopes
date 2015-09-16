# Sofa/Eloquent-Scopes

Handy scopes for Eloquent (Laravel) query builder.


## Installation

Package requires **PHP 5.4+** and works with **Laravel 5/5.1**.

1. Require the package in your `composer.json`:
    ```
        "require": {
            ...
            "sofa/eloquent-scopes": "~1.0",
        },
    ```

2. Add trait to your class, eg. `use \Sofa\EloquentScopes\PeriodScopes`;


## Usage example

### `PeriodScopes` - provides methods for easy fetching records in given range, relative to NOW.

```php
class Subscription extends Model
{
    use PeriodScopes;

    // optionally you may provide the column to be filtered
    //   By default self::CREATED_AT -> 'created_at' will be used
    const PERIOD_COLUMN = 'expires_at';
}

class Subscription extends Model
{
    use PeriodScopes;
}
```

```php
// Given it's September 11th, 2015

// count users created in August
User::lastMonth()->count();

// get users created on September 10th
User::yesterday()->get();

// count users who logged-in in 2014 & 2015
User::periods('year', 1, 'last_login', true)->count();

// count users created in 2014 & 2015
User::periods('year', -1, null, true)->count();
// or
User::periods('year', -1, true)->count();

// Get subscriptions expiring in October
User::nextMonth()->get();

// Get subscriptions expired in past 7 days
User::periods('day', -7)->get();

// Get subscriptions expiring in next 30 days
User::periods('day', 30)->get();


//
// Obviously these are query extensions, so you can chain them however you like
// 
User::query()->tomorrow()->get();
User::where(...)->tomorrow()->get();
(new User)->tomorrow()->get();
```


## Roadmap

TBA
