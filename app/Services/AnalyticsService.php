<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Blog;
use App\Models\Import;
use App\Models\Mailer;
use App\Models\Message;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\Subscription;
use App\Enums\NotificationGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\Activitylog\Models\Activity;

class AnalyticsService
{
    public function __construct()
    {
        
    }

    public function getWidget($class)
    {
        $data = [];

        if ($class == Feedback::class) {
            $models = Feedback::all();
            $data['from-users'] = $models->whereNotNull('user_id')->count();
        } else if ($class == Import::class) {
            $models = Import::all();
            $data['success'] = $models->where('status', 'success')->count();
        } else if ($class == Mailer::class) {
            $models = Mailer::all();
            $data['inactive'] = $models->where('is_active', false)->count();
        } else if ($class == Post::class) {
            $models = Post::all();
            $data['inactive'] = $models->where('is_active', false)->count();
        } else if ($class == User::class) {
            $models = User::all();
            $data['online'] = $models->where('last_active_at', '>=', now()->subMinutes(User::ONLINE_MINUTES))->count();
        } else if ($class == 'blog_views') {
            $models = Activity::query()
                ->where('log_name', 'models')
                ->where('event', 'view')
                ->where('properties->is_fake', false)
                ->where('subject_type', Blog::class)
                ->get();
        } else if ($class == 'user_views') {
            $models = Activity::query()
                ->where('log_name', 'models')
                ->where('event', 'view')
                ->where('properties->is_fake', false)
                ->where('subject_type', User::class)
                ->get();
        } else if ($class == 'post_views') {
            $models = Activity::query()
                ->where('log_name', 'models')
                ->where('event', 'view')
                ->where('properties->is_fake', false)
                ->where('subject_type', Post::class)
                ->get();
        } else if ($class == Notification::class) {
            $models = Notification::all();
        } else if ($class == Message::class) {
            $models = Message::all();
        } else if ($class == Subscription::class) {
            $models = Subscription::all();
        } else {
            dd('class not found', $class);
        }

        $data['total'] = $models->count();
        $map = [
            '1d' => now()->subDay(),
            '2d' => now()->subDays(2),
            '1w' => now()->subWeek(),
            '2w' => now()->subWeeks(2),
            '1m' => now()->subMonth(),
            '2m' => now()->subMonths(2)
        ];

        foreach ($map as $time => $date) {
            $data[$time] = (clone $models)->where('created_at', '>=', $date)->count();
        }

        return $data;
    }

    public function getChart($chartType)
    {
        if ($chartType == 'models-created') {
            $result = [
                [
                    'label' => 'Users',
                    'data' => $this->constructChartData(User::query())
                ],
                [
                    'label' => 'Posts',
                    'data' => $this->constructChartData(Post::query())
                ],
                [
                    'label' => 'Mailers',
                    'data' => $this->constructChartData(Mailer::query())
                ],
                [
                    'label' => 'Imports',
                    'data' => $this->constructChartData(Import::query())
                ],
                [
                    'label' => 'Feedback',
                    'data' => $this->constructChartData(Feedback::query())
                ],
                [
                    'label' => 'Post Views',
                    'data' => $this->constructChartData(Post::getAllViews())
                ],
                [
                    'label' => 'Blog Views',
                    'data' => $this->constructChartData(Blog::getAllViews())
                ],
                [
                    'label' => 'User Views',
                    'data' => $this->constructChartData(User::getAllViews())
                ],
                [
                    'label' => 'Notifications',
                    'data' => $this->constructChartData(Notification::query())
                ],
                [
                    'label' => 'Messages',
                    'data' => $this->constructChartData(Message::query())
                ],
                [
                    'label' => 'Subscriptions',
                    'data' => $this->constructChartData(Subscription::query())
                ],
            ];
        } elseif ($chartType == 'users-per-country') {
            $users = User::query()
                ->select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->get()
                ->toArray();
            $result = [];

            foreach ($users as $user) {
                $c = strtoupper($user['country']);
                $result[$c] = $user['total'];
            }
        } elseif ($chartType == 'posts-per-locale') {
            $posts = Post::query()
                ->select('origin_lang', DB::raw('count(*) as total'))
                ->groupBy('origin_lang')
                ->get()
                ->toArray();
            $result = [];

            foreach ($posts as $post) {
                $c = strtoupper($post['origin_lang']);
                $result[$c] = $post['total'];
            }
        } elseif ($chartType == 'mailer-emails') {
            $query = Notification::query()
                ->where('group', NotificationGroup::MAILER_SEND);
            $result = $this->constructChartData($query);
        } else {
            dd('Chart type undefield', $chartType);
        }

        return $result;
    }

    public function getTable($type)
    {
        if ($type == 'users-by-posts-count') {
            $result = User::limit(5)->withCount('posts')->orderBy('posts_count', 'desc')->get();
        } elseif ($type == 'users-by-mailers-count') {
            $result = User::limit(5)->withCount('mailers')->orderBy('mailers_count', 'desc')->get();
        } elseif ($type == 'users-by-imports-count') {
            $result = User::limit(5)->withCount('imports')->orderBy('imports_count', 'desc')->get();
        } elseif ($type == 'users-by-post-views-count') {
            $result = DB::table('users')
                ->join('posts', 'users.id', '=', 'posts.user_id')
                ->join('activity_log', function ($join) {
                    $join->on('posts.id', '=', 'activity_log.subject_id')
                        ->where('activity_log.subject_type', '=', 'App\\Models\\Post')
                        ->where('activity_log.log_name', '=', 'models')
                        ->where('activity_log.event', '=', 'view');
                })
                ->select('users.*', DB::raw('COUNT(activity_log.id) as total_views'))
                ->groupBy('users.id')
                ->orderBy('total_views', 'desc')
                ->take(5)
                ->get();
        } else {
            abort(500, 'table type not found');
        }

        return $result;
    }

    private function constructChartData($query)
    {
        $period = explode(' - ', request()->period);
        $from = $period[0];
        $to = $period[1];
        $result = [];
        $models = $query
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->groupBy('date')
            ->get();

        // fill the dates
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        $diff = $from->diffInDays($to);

        for ($i=0; $i < $diff+1; $i++) {
            $date = (clone $from)->addDays($i)->timestamp * 1000;
            $result[$date] = [
                'x' => $date,
                'y' => 0
            ];
        }

        // fill actual data
        foreach ($models as $model) {
            $date = Carbon::parse($model->date)->timestamp * 1000;
            $result[$date]['y'] = $model->count;
        }

        usort($result, fn ($a, $b) => $a['x'] <=> $b['x']);

        return $result;
    }
}
