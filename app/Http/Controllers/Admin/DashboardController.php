<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Blog;
use App\Models\Import;
use App\Models\Mailer;
use App\Models\Message;
use App\Models\Feedback;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'usersNumbers' => $this->getUsersNumbers(),
            'importsNumbers' => $this->getImportsNumbers(),
            'mailersNumbers' => $this->getMailersNumbers(),
            'postsNumbers' => $this->getPostsNumbers(),
            'feedbacksNumbers' => $this->getFeedbacksNumbers(),
            'messagesNumbers' => $this->getMessagesNumbers(),
            'postViewsNumbers' => $this->getPostViewsNumbers(),
            'blogViewsNumbers' => $this->getBlogViewsNumbers(),
            'notificationViewsNumbers' => $this->getNotificationViewsNumbers(),
        ];

        return view('admin.index', $data);
    }

    public function modelsCreated(Request $request)
    {
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
        ];

        return $this->jsonSuccess('', $result);
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
            $result[] = [
                'x' => (clone $from)->addDays($i)->timestamp * 1000,
                'y' => 0
            ];
        }

        // fill actual data
        foreach ($models as $model) {
            $date = Carbon::parse($model->date)->timestamp * 1000;
            $result[] = [
                'x' => $date,
                'y' => $model->count
            ];
        }

        usort($result, fn ($a, $b) => $a['x'] <=> $b['x']);

        return $result;
    }

    private function getFeedbacksNumbers()
    {
        $models = Feedback::all();
        $data = $this->getDataByCreatedAt($models);
        $data['from-users'] = $models->whereNotNull('user_id')->count();
        $data['total'] = $models->count();

        return $data;
    }

    private function getImportsNumbers()
    {
        $models = Import::all();
        $data = $this->getDataByCreatedAt($models);
        $data['success'] = $models->where('status', 'success')->count();
        $data['total'] = $models->count();

        return $data;
    }

    private function getMailersNumbers()
    {
        $models = Mailer::all();
        $data = $this->getDataByCreatedAt($models);
        $data['inactive'] = $models->where('is_active', false)->count();
        $data['total'] = $models->count();

        return $data;
    }

    private function getPostsNumbers()
    {
        $models = Post::all();
        $data = $this->getDataByCreatedAt($models);
        $data['inactive'] = $models->where('is_active', false)->count();
        $data['total'] = $models->count();

        return $data;
    }

    private function getUsersNumbers()
    {
        $models = User::all();
        $data = $this->getDataByCreatedAt($models);
        $data['online'] = $models->where('last_active_at', '>=', now()->subMinutes(User::ONLINE_MINUTES))->count();
        $data['total'] = $models->count();

        return $data;
    }

    private function getBlogViewsNumbers()
    {
        $models = Activity::query()
            ->where('log_name', 'models')
            ->where('event', 'view')
            ->where('properties->is_fake', false)
            ->where('subject_type', Blog::class)
            ->get();
        $data = $this->getDataByCreatedAt($models);
        $data['total'] = $models->count();

        return $data;
    }

    private function getPostViewsNumbers()
    {
        $models = Activity::query()
            ->where('log_name', 'models')
            ->where('event', 'view')
            ->where('properties->is_fake', false)
            ->where('subject_type', Post::class)
            ->get();
        $data = $this->getDataByCreatedAt($models);
        $data['total'] = $models->count();

        return $data;
    }

    private function getNotificationViewsNumbers()
    {
        $models = Notification::all();
        $data = $this->getDataByCreatedAt($models);
        $data['total'] = $models->count();

        return $data;
    }

    private function getMessagesNumbers()
    {
        $models = Message::all();
        $data = $this->getDataByCreatedAt($models);
        $data['total'] = $models->count();

        return $data;
    }

    private function getDataByCreatedAt($collection)
    {
        $map = [
            '1d' => now()->subDay(),
            '2d' => now()->subDays(2),
            '1w' => now()->subWeek(),
            '2w' => now()->subWeeks(2),
            '1m' => now()->subMonth(),
            '2m' => now()->subMonths(2)
        ];

        foreach ($map as $time => $date) {
            $data[$time] = (clone $collection)->where('created_at', '>=', $date)->count();
        }

        return $data;
    }
}
