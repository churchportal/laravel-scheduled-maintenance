<?php

namespace Churchportal\ScheduledMaintenance\Events;

use Churchportal\ScheduledMaintenance\Models\ScheduledMaintenanceModel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceCompleted
{
    use Dispatchable;
    use SerializesModels;

    public ScheduledMaintenanceModel $scheduledMaintenance;

    public function __construct(ScheduledMaintenanceModel $scheduledMaintenance)
    {
        $this->scheduledMaintenance = $scheduledMaintenance;
    }
}
