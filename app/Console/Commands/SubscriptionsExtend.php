<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\SubscriptionService;

class SubscriptionsExtend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:extend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking activity subscriptions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle()
    {
        try {
            SubscriptionService::extend();
        } catch (\Throwable $th) {
            Log::channel('commands')->error('[' . $this->signature . '] ' . $this->description . ' FAILS. ' . $th->getMessage());
        }

        return Command::SUCCESS;
    }
}
