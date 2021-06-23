<?php

namespace Churchportal\ScheduledMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class MaintenanceUpCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'maintenance:up';
    protected $description = 'Bring your application out of maintenance mode';

    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        app('maintenance')->up();

        $this->info('Application is live!');
    }
}
