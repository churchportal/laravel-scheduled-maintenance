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
