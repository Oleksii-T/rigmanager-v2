<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Post;
use App\Models\Blog;
use App\Models\Import;
use App\Models\Mailer;
use App\Models\Message;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request, AnalyticsService $service)
    {
        $data = [
            'usersNumbers' => $service->getWidget(User::class),
            'importsNumbers' => $service->getWidget(Import::class),
            'mailersNumbers' => $service->getWidget(Mailer::class),
            'postsNumbers' => $service->getWidget(Post::class),
            'feedbacksNumbers' => $service->getWidget(Feedback::class),
            'messagesNumbers' => $service->getWidget(Message::class),
            'postViewsNumbers' => $service->getWidget('post_views'),
            'blogViewsNumbers' => $service->getWidget('blog_views'),
            'userViewsNumbers' => $service->getWidget('user_views'),
            'notificationViewsNumbers' => $service->getWidget(Notification::class),
            'subscriptionsNumbers' => $service->getWidget(Subscription::class),
            'usersByPostsCount' => $service->getTable('users-by-posts-count'),
            'usersByMailersCount' => $service->getTable('users-by-mailers-count'),
            'usersByImportsCount' => $service->getTable('users-by-imports-count'),
            'usersByPostViewsCount' => $service->getTable('users-by-post-views-count'),
            'usersByViewsCount' => $service->getTable('users-by-views-count'),
            'usersByContactViewsCount' => $service->getTable('users-by-contact-views-count'),
            'usersByEngagement' => $service->getTable('users-by-engagement'),
            'postsByViewsCount' => $service->getTable('posts-by-views-count'),
            'postsByPriceRequestsCount' => $service->getTable('posts-by-price-requests-count'),
            'postsByOldestView' => $service->getTable('posts-by-oldest-view'),
            'postsWithoutViews' => $service->getTable('posts-without-views'),
            'categoriesByPostsCount' => $service->getTable('categories-by-posts-count'),
            'categoriesByPostsViews' => $service->getTable('categories-by-posts-views'),
            'mailersByEmails' => $service->getTable('mailers-by-emails'),
            'mailersByOldestEmail' => $service->getTable('mailers-by-oldest-emails'),
            'mailersWithoutEmail' => $service->getTable('mailers-without-emails'),
        ];

        return view('admin.index', $data);
    }

    public function icons()
    {
        return view('admin.icons');
    }

    public function getChart(Request $request, $type, AnalyticsService $service)
    {
        $result = $service->getChart($type);

        return $this->jsonSuccess('', $result);
    }
}
