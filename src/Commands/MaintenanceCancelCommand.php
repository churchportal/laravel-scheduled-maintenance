<?php

namespace Churchportal\ScheduledMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class MaintenanceCancelCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'maintenance:cancel {id}';
    protected $description = 'Delete a scheduled maintenance window';

    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        if (app('maintenance')->isDown() || app()->isDownForMaintenance()) {
            $this->error('Application is in maintenance mode!');
            die;
        }

        $deleted = app('maintenance')->delete($this->argument('id'));

        if ($deleted) {
            $this->info('Scheduled maintenance has been cancelled!');
        } else {
            $this->error('Unable to locate a record to delete!');
        }
    }
}
