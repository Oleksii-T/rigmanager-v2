<?php

namespace App\Console\Commands;

use Log;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class DevImportViewsToActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:views-to-activity';

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
        $views = \DB::table('views')->get();
        $bar = $this->output->createProgressBar(count($views));

        foreach ($views as $view) {
            $data = [
                'log_name' => 'models',
                'event' => 'view',
                'description' => '',
                'subject_type' => $view->viewable_type,
                'subject_id' => $view->viewable_id,
                'properties' => json_encode([
                    'ip' => $view->ip,
                    'agent' => null,
                    'from' => null,
                    'location' => $view->is_fake ? null : \Stevebauman\Location\Facades\Location::get($view->ip)?->countryCode,
                    'agent_info' => [],
                    'is_fake' => (bool)$view->is_fake,
                    'from_view' => $view->id
                ]),
                'created_at' => $view->created_at,
                'updated_at' => $view->created_at
            ];

            if ($view->user_id) {
                $data['causer_type'] = User::class;
                $data['causer_id'] = $view->user_id;
            }

            Activity::insert($data);

            $bar->advance();
        }

        $bar->finish();
        $this->line('');

        return 0;
    }
}



