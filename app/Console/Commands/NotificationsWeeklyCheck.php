<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\NotificationGroup;
use App\Models\Notification;
use App\Models\View;
use App\Models\Post;
use App\Models\User;
use App\Models\Setting;
use Log;

class NotificationsWeeklyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:weekly-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse app data to send weekly notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            //posts views
            $min = Setting::get('notif_weekly_posts_views_min');
            $postsByUser = Post::query()
                ->whereRelation('views', 'created_at', '>=', now()->subWeek())
                ->withCount(['views'=> function ($q) {
                    $q->where('created_at', '>=', now()->subWeek());
                }])
                ->get()
                ->groupBy('user_id');

            foreach ($postsByUser as $uId => $items) {
                $count = $items->pluck('views_count')->sum();
                if (!$count || $count < $min) {
                    continue;
                }
                Notification::make($uId, NotificationGroup::WEEKLY_POSTS_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }

            // contact views
            $min = Setting::get('notif_weekly_contacts_views_min');
            $viewsByUser = View::query()
                ->where('viewable_type', 'UserContacts')
                ->where('created_at', '>=', now()->subWeek())
                ->get()
                ->groupBy('viewable_id');
            foreach ($postsByUser as $uId => $items) {
                $count = $items->count();

                if (!$count || $count < $min) {
                    continue;
                }

                Notification::make($uId, NotificationGroup::WEEKLY_CONTACS_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }

            // user page views
            $min = Setting::get('notif_weekly_profile_views_min');
            $viewsByUser = View::query()
                ->where('viewable_type', User::class)
                ->where('created_at', '>=', now()->subWeek())
                ->get()
                ->groupBy('viewable_id');
            foreach ($viewsByUser as $uId => $items) {
                $count = $items->count();

                if (!$count || $count < $min) {
                    continue;
                }

                Notification::make($uId, NotificationGroup::WEEKLY_PROFILE_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . $th->getMessage());
        }

        return 0;
    }
}
