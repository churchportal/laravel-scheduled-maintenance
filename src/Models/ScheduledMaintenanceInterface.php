<?php


namespace Churchportal\ScheduledMaintenance\Models;


interface ScheduledMaintenanceInterface
{
    public function redirectTo(): ?string;
    public function statusCode(): ?int;
    public function bypassSecret(): ?string;
}
