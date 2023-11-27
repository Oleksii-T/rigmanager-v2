<?php

namespace App\Console\Commands;

use Log;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class DevFormatActivityLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:format-activity-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logs = Activity::whereNotIn('event', ['created', 'updated', 'deleted'])->get();
        $bar = $this->output->createProgressBar(count($logs));

        foreach ($logs as $log) {
            $props = $log->properties->toArray();

            if (array_key_exists('general_info', $props)) {
                $bar->advance();
                continue;
            }

            if ($log->event == 'view') {
                $newProps = $props;
                unset($newProps['is_fake']);
                $newProps = [
                    'general_info' => $newProps,
                    'is_fake' => $props['is_fake']
                ];
            } else {
                $newProps = [
                    'general_info' => $props
                ];
            }

            $log->properties = collect($newProps);
            $log->save();

            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        return 0;
    }
}



