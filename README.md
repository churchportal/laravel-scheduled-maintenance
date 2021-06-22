# Laravel Scheduled Maintenance

This package enables you to schedule maintenance windows for your Laravel application, make it easier to notify users about upcoming maintenance, and also customize the user experience for your users while the application is down for maintenance.

It's built in a similar fashion to laravel's preexisting `down` and `up` commands with support for bypass mode, redirects, and custom HTTP status codes.

**NOTE:** This package does rely on your database, if you are preforming significant DB work during a maintenance window then you may want to consider using laravel's `down` for that work instead. 
## Installation

Install the package via composer:

```bash
composer require churchportal/laravel-scheduled-maintenance
```
Publish the package assets (this will publish the config file, 1 migration, and and example blade view):
```bash
php artisan vendor:publish --provider="Churchportal\ScheduledMaintenance\ScheduledMaintenanceServiceProvider"
```
Run the migration:
```bash
php artisan migrate
```

## Configuration

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Prerequisites
    |--------------------------------------------------------------------------
    | Configure the table name and model for maintenance windows.  You can use
    | your own model as long as it extends the default class below.
    */

    'table_name' => 'scheduled_maintenance',
    'model' => \Churchportal\ScheduledMaintenance\Models\ScheduledMaintenanceModel::class,

    /*
    |--------------------------------------------------------------------------
    | Maintenance Defaults
    |--------------------------------------------------------------------------
    | These defaults will be used when scheduling maintenance windows or moving
    | your application to maintenance mode. You can customize these for each
    | maintenance window as needed.
    */

    'redirect_to' => null,
    'status_code' => 503,
    'bypass_secret' => null,

    /*
    |--------------------------------------------------------------------------
    | Bypass Cookie Name
    |--------------------------------------------------------------------------
    | This cookie will be created when you visit the bypass secret url. It's
    | recommended that you customize this cookie name to your env or app name
    */

    'bypass_cookie_name' => 'laravel_maintenance',

    /*
    |--------------------------------------------------------------------------
    | Excluded URIs
    |--------------------------------------------------------------------------
    | These paths will still be accessible during maintenance mode.  These will
    | apply to any maintenance windows so use with caution!
    */

    'except' => [
        'status',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blade View
    |--------------------------------------------------------------------------
    | This view will be rendered when you application is in maintenance mode.
    | You should use your own view, the default we've provided is just an
    | example of how you could implement something.
    */

    'view' => 'scheduled-maintenance::down',
];

```
## The `app('maintenance')` singleton
The `ScheduledMaintenace` class is bound to the container via the `maintenance` key.
These class methods enable you to:
 - Check if the application is in maintenance mode `->isDown()`
 - Move into maintenance mode `->down()`
 - Move out of maintenance mode `->up()`
 - Get the currently active maintenance window `->current()`
 - Get the next scheduled maintenance window `->next()`
 - Check if you have bypassed maintenance mode `->inBypassMode()`
 - Check if there is a notice available for users about upcoming maintenance `->notice()`
 - And More!
 
## Artisan Commands

### `maintenance:schedule`
This command will walk you through the process of creating a new maintenance window.  You'll be prompted for information like when the maintenance will start, when users should see a notification about the upcoming maintenance, etc.

### `maintenance:down`
**This command will immediately move your application into maintenance mode!**  When running this command the package will either move your next scheduled maintenance window into an active state or it will create a new record if there isn't one already scheduled.

#### Options when creating a new record:
- `--bypass-secret=` Set the bypass secret for this maintenance window
- `--redirect-to=` Configure a redirect for your users while the application is in maintenance mode

### `maintenance:up`
This command will move your application out of maintenance mode

### `maintenance:upcoming`
This command will list all of your future maintenance windows in a table format

## Events
All events include a public `$scheduledMaintenace` model property

### `MaintenanceScheduled`
This event is triggered after scheduling maintenance using the `maintenance:schedule` command

### `MaintenanceStarted`
This event is triggered after running `app('maintenance')->down()`.
There is an additional `$wasPreviouslyScheduled` property that will be false if the maintenance was started without being previous scheduled.

### `MaintenanceCompleted`
This event is triggered after running `app('maintenance')->up()`

## Usage

### Display notice to users about upcoming maintenance
Using the `app('maintenance')->notice()` method you'll have access to the details about the next upcoming maintenance window

#### in blade
```blade
@extends('layout')

@if(app('maintenance')->notice()) 
    <p>
        We'll be preforming server maintenance on {{ app('maintenance')->notice()->starts_at->format('F jS, \a\t g:ia') }}
    </p>
@endif
```

#### in inerita
```php
// In your Inerita middleware

\Inertia\Inertia::share([
    'upcomingMaintenance' => app('maintenance')->notice(),
]);
```

```vue
<!-- In your Inertia layout -->

<UpcomingMaintenance v-if="$page.props.upcomingMaintenance" />
```

### Bypassing maintenance mode
You can bypass maintenance mode by navigating to the `bypass_secret` url.
It can be useful when testing your application to remember that it's still in maintenance mode.
Here are some examples of how you can implement that notice: 

#### in blade
```blade
@extends('layout')

@if(app('maintenance')->inBypassMode()) 
    <p>
        Your application is currently in maintenance mode!  It should last until {{ app('maintenance')->current()->ends_at }}
    </p>
@endif
```

#### in inerita
```php
// In your Inerita middleware

\Inertia\Inertia::share([
    'bypassedMaintenance' => app('maintenance')->inBypassMode(),
]);
```

```vue
<!-- In your Inertia layout -->

<BypassedMaintenanceBanner v-if="$page.props.bypassedMaintenance" />
```
