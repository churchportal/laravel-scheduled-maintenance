<?php

namespace Churchportal\ScheduledMaintenance;

use Churchportal\ScheduledMaintenance\Commands\MaintenanceDownCommand;
use Churchportal\ScheduledMaintenance\Commands\MaintenanceUpCommand;
use Churchportal\ScheduledMaintenance\Commands\MaintenanceUpcomingCommand;
use Churchportal\ScheduledMaintenance\Commands\ScheduleMaintenanceCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScheduledMaintenanceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-scheduled-maintenance')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scheduled_maintenance_table')
            ->hasCommands([
                ScheduleMaintenanceCommand::class,
                MaintenanceDownCommand::class,
                MaintenanceUpCommand::class,
                MaintenanceUpcomingCommand::class,
            ]);
    }

    public function packageRegistered()
    {
        $this->app->singleton('maintenance', ScheduledMaintenance::class);
    }
}
