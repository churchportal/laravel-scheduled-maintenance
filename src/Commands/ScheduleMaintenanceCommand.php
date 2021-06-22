<?php

namespace Churchportal\ScheduledMaintenance\Commands;

use Churchportal\ScheduledMaintenance\Events\MaintenanceScheduled;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class ScheduleMaintenanceCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'maintenance:schedule';
    protected $description = 'Schedule a new maintenance window';

    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $model = new (config('scheduled-maintenance.model'));

        $startsAt = now()->addWeek()->setTime(5, 0, 0);
        $endsAt = now()->addWeek()->addDay()->setTime(7, 0, 0);
        $displayNoticeAt = now()->addDays(5)->setTime(8, 0, 0);

        $scheduled = $model->create([
            'title' => $this->ask('Title'),
            'description' => $this->ask('Description'),
            'settings' => [
                'redirect_to' => $this->ask('Redirect to', config('scheduled-maintenance.redirect_to')),
                'status_code' => $this->ask('Status', config('scheduled-maintenance.status_code')),
                'bypass_secret' => $this->ask('Secret for bypassing maintenance mode', config('scheduled-maintenance.bypass_secret') ?? app(\Faker\Provider\Uuid::class)::uuid()),
            ],
            'starts_at' => $this->ask('Maintenance Starts', $startsAt->toDateTimeString()),
            'ends_at' => $this->ask('Maintenance Ends', $endsAt->toDateTimeString()),
            'display_notice_at' => $this->ask('When should users see a notice about this maintenance window?', $displayNoticeAt->toDateTimeString()),
        ]);

        event(new MaintenanceScheduled($scheduled));

        $this->info('Maintenance Scheduled!');
    }
}
