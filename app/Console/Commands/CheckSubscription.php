<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Console\Command;
use App\Enums\NotificationGroup;
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

                $user = $subscription->user;
                $stripeSub = $stripeService->getSubscription($subscription->stripe_id);
                $stripeSubStatus = $stripeSub->status;
                $nextDate = Carbon::createFromTimestamp($stripeSub->current_period_start);
                $activeCycle = $subscription->cycle;

                if ($activeCycle->expire_at < $nextDate) {
                    // current cycle should be renewed

                    if ($stripeSubStatus != 'active') {
                        // stripe can not renew subscription automaticaly
                        $subscription->cancel();
                        $activeCycle->deactivate();

                        Notification::make($user->id, NotificationGroup::SUB_TERMINATED_CAUSE_STRIPE, [
                            'vars' => [
                                'title' => $subscription->plan->title,
                            ]
                        ], $subscription);

                        continue;
                    }

                    if ($subscription->status != 'active') {
                        // move from trialing to active status
                        $subscription->update([
                            'status' => 'active'
                        ]);
                    }

                    $activeCycle->deactivate();
                    $cycle = $subscription->cycles()->create([
                        'is_active' => true,
                        'expire_at' => $nextDate
                    ]);

                    Notification::make($user->id, NotificationGroup::SUB_EXTENDED, [
                        'vars' => [
                            'title' => $subscription->plan->title,
                        ]
                    ], $cycle);

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

                    Notification::make($user->id, NotificationGroup::SUB_CANCELED_EXPIRED, [
                        'vars' => [
                            'title' => $subscription->plan->title,
                        ]
                    ], $activeCycle);
                }
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error('[' . $this->signature . '] ' . $this->description . ' FAILS. ' . $th->getMessage());
        }

        return Command::SUCCESS;
    }
}
