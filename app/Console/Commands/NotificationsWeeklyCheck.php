<?php

namespace App\Console\Commands;

use Log;
use App\Models\Post;
use App\Models\User;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Enums\NotificationGroup;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\Models\Activity;

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
            $sendToNonReg = Setting::get('non_reg_send_notif_analytics_to_email');

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
                $user = User::fing($uId);
                $count = $items->pluck('views_count')->sum();

                if (!$count || $count < $min) {
                    continue;
                }

                if (!$user->info->is_registered && $sendToNonReg) {
                    Mail::to($user->getEmails(0))->send(new \App\Mail\WeeklyPostViewsForNonReg($user, $count, $items));
                }

                Notification::make($uId, NotificationGroup::WEEKLY_POSTS_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }

            // contact views
            $min = Setting::get('notif_weekly_contacts_views_min');
            $viewsByUser = Activity::query()
                ->where('log_name', 'users')
                ->where('event', 'contacts')
                ->where('created_at', '>=', now()->subWeek())
                ->get()
                ->groupBy('subject_id');
            foreach ($postsByUser as $uId => $items) {
                $user = User::fing($uId);
                $count = $items->count();

                if (!$count || $count < $min) {
                    continue;
                }

                if (!$user->info->is_registered && $sendToNonReg) {
                    Mail::to($user->getEmails(0))->send(new \App\Mail\WeeklyContactViewsForNonReg($user, $count));
                }

                Notification::make($uId, NotificationGroup::WEEKLY_CONTACS_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }

            // user profile views
            $min = Setting::get('notif_weekly_profile_views_min');
            $viewsByUser = User::getAllViews(true)
                ->where('created_at', '>=', now()->subWeek())
                ->get()
                ->groupBy('subject_id');
            foreach ($viewsByUser as $uId => $items) {
                $user = User::fing($uId);
                $count = $items->count();

                if (!$count || $count < $min) {
                    continue;
                }

                if (!$user->info->is_registered && $sendToNonReg) {
                    Mail::to($user->getEmails(0))->send(new \App\Mail\WeeklyProfileViewsForNonReg($user, $count));
                }

                Notification::make($uId, NotificationGroup::WEEKLY_PROFILE_VIEWS, [
                    'vars' => [
                        'count' => $count
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . exceptionAsString($th));
        }

        return 0;
    }
}
