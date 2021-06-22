<?php

namespace Churchportal\ScheduledMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MaintenanceUpcomingCommand extends Command
{
    protected $signature = 'maintenance:upcoming';
    protected $description = 'List your upcoming scheduled maintenance windows';

    public function handle()
    {
        $model = new (config('scheduled-maintenance.model'));
        $tableData = [];

        $model->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->each(function ($row) use (&$tableData) {
                $activeColor = $row->active ? 'green' : 'red';
                $active = $row->active ? 'Yes' : 'No';

                $tableData[] = [
                    $row->id,
                    $row->title,
                    Str::limit($row->description, 128). '...',
                    $row->redirectTo(),
                    $row->statusCode(),
                    $row->bypassSecret(),
                    $row->starts_at->format('m/d/Y g:i A'),
                    $row->ends_at ? $row->ends_at->format('m/d/Y g:i A') : '',
                    $row->display_notice_at ? $row->display_notice_at->format('m/d/Y g:i A') : '',
                    "<fg=$activeColor>$active</>",
                ];
            });

        $this->table([
            'ID', 'Title', 'Description', 'Redirect To', 'Status Code', 'Bypass Secret', 'Starts At', 'Ends At', 'Display Notice At', 'Active?',
        ], $tableData);
    }
}
