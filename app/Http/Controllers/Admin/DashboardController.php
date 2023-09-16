<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Import;
use App\Models\Mailer;
use App\Models\Blog;
use App\Models\Feedback;
use App\Models\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'mailersNumbers' => $this->getMailersNumbers(),
            'postViewsNumbers' => $this->getPostViewsNumbers(),
            'blogViewsNumbers' => $this->getBlogViewsNumbers(),
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
                'data' => $this->constructChartData(View::where('viewable_type', Post::class)->where('is_fake', false))
            ],
        ];

        return $this->jsonSuccess('', $result);
    }

    private function constructChartData($query)
    {
        $from = request()->from ?? now()->subMonth();
        $result = [];
        $to = request()->to ?? now();
        $models = $query
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereDate('created_at', '<=', $to)
            ->whereDate('created_at', '>=', $from)
            ->groupBy('date')
            ->get();

        foreach ($models as $model) {
            $date = Carbon::createFromFormat('Y-m-d', $model->date)->timestamp * 1000;
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
        $models = View::where('is_fake', false)->where('viewable_type', Blog::class)->get();
        $data = $this->getDataByCreatedAt($models);
        $data['total'] = $models->count();

        return $data;
    }

    private function getPostViewsNumbers()
    {
        $models = View::where('is_fake', false)->where('viewable_type', Post::class)->get();
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
