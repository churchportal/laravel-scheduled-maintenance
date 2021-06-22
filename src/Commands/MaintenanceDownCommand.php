<?php

namespace Churchportal\ScheduledMaintenance\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class MaintenanceDownCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'maintenance:down {--bypass-secret=} {--redirect-to=}';
    protected $description = 'Put your application into maintenance mode';

    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        if (app('maintenance')->isDown() || app()->isDownForMaintenance()) {
            $this->error('Application is already in maintenance mode!');
            die;
        }

        $model = app('maintenance')->down([
            'settings' => [
                'bypass_secret' => $this->option('bypass-secret'),
                'redirect_to' => $this->option('redirect-to'),
            ],
        ]);

        $this->output->writeln([
            "<fg=green>Application is now in maintenance mode!</>",
            "",
            "<fg=yellow>UUID: </>{$model->uuid}",
            "<fg=yellow>Title: </>". $model->title,
            "<fg=yellow>Description: </>". Str::limit($model->description, 128). '...',
            "<fg=yellow>Redirect To: </>". $model->redirectTo(),
            "<fg=yellow>Status Code: </>". $model->statusCode(),
            "<fg=yellow>Bypass Secret: </>". $model->bypassSecret(),
            sprintf("<fg=yellow>Starts At: </>%s", $model->starts_at ? $model->starts_at->format('m/d/Y g:i A') : ''),
            sprintf("<fg=yellow>Ends At: </>%s", $model->ends_at ? $model->ends_at->format('m/d/Y g:i A') : ''),
            sprintf("<fg=yellow>Display Notice At: </>%s", $model->display_notice_at ? $model->display_notice_at->format('m/d/Y g:i A') : ''),
        ]);
    }
}
