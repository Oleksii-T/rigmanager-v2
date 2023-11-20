<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscriptions';

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
            $stripeService = new StripeService();

            $validStatuses = ['trialing', 'active'];
            foreach (Subscription::whereIn('status', $validStatuses)->get() as $subscription) {
                if (!$subscription->stripe_id) {
                    continue;
                }

                $stripeSub = $stripeService->getSubscription($subscription->stripe_id);
                $stripeSubStatus = $stripeSub->status;
                $nextDate = Carbon::createFromTimestamp($stripeSub->current_period_start);
                $activeCycle = $subscription->cycle;

                if ($activeCycle->expire_at < $nextDate) {
                    // current cycle should be renewed

                    if ($stripeSubStatus != 'active') {
                        // stripe can not renew subscription automaticaly
                        $activeCycle->deactivate();
                        $subscription->cancel();
                        continue;
                    }

                    $subscription->update([ // move from trialing to active status
                        'status' => 'active'
                    ]);
                    $activeCycle->deactivate();
                    $c = $subscription->cycles()->create([
                        'is_active' => true,
                        'expire_at' => $nextDate
                    ]);

                    continue;
                }

                if (!in_array($stripeSubStatus, $validStatuses)) {
                    // stripe sub was canceled from outside within a active cycle.
                    $subscription->cancel();
                }
            }

            $now = Carbon::now();
            // parse manualy canceled subs.
            foreach (Subscription::where('status', 'canceled')->get() as $subscription) {
                $activeCycle = $subscription->cycle;
                if ($activeCycle && $activeCycle->expire_at < $now) {
                    $activeCycle->deactivate();
                }
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error('[' . $this->signature . '] ' . $this->description . ' FAILS. ' . $th->getMessage());
        }

        return Command::SUCCESS;
    }
}
