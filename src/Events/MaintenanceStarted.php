<?php

namespace Churchportal\ScheduledMaintenance\Events;

use Churchportal\ScheduledMaintenance\Models\ScheduledMaintenanceModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceStarted
{
    use Dispatchable, SerializesModels;

    public ScheduledMaintenanceModel $scheduledMaintenance;
    private bool $wasPreviouslyScheduled;

    public function __construct(ScheduledMaintenanceModel $scheduledMaintenance, $wasPreviouslyScheduled = false)
    {
        $this->scheduledMaintenance = $scheduledMaintenance;
        $this->wasPreviouslyScheduled = $wasPreviouslyScheduled;
    }
}
