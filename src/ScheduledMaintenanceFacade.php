<?php

namespace Churchportal\ScheduledMaintenance;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Churchportal\ScheduledMaintenance\ScheduledMaintenance
 */
class ScheduledMaintenanceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-scheduled-maintenance';
    }
}
