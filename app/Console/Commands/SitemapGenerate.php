<?php

namespace App\Console\Commands;

use Log;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class SitemapGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the sitemap.xml';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $paths = [
                'master' => public_path('sitemap.xml'),
                'posts' => public_path('sitemap-posts.xml'),
                'categories' => public_path('sitemap-categories.xml'),
                'blogs' => public_path('sitemap-blogs.xml'),
                'users' => public_path('sitemap-users.xml'),
            ];

            $postLastUpdatedAt = Post::latest('updated_at')->value('updated_at');
            $categoryLastUpdatedAt = Category::latest('updated_at')->value('updated_at');
            $userLastUpdatedAt = User::latest('updated_at')->value('updated_at');
            $blogLastUpdatedAt = Blog::latest('updated_at')->value('updated_at');
            $staticPagesUpdatedAt = Carbon::parse('01/01/2024'); // m/d/y
            $aboutUsUpdatedAt = Carbon::parse('07/05/2024');

            // master sitemap
            $sitemap = Sitemap::create()
                ->add(Url::create('/sitemap-posts.xml')->setLastModificationDate($postLastUpdatedAt))
                ->add(Url::create('/sitemap-categories.xml')->setLastModificationDate($categoryLastUpdatedAt))
                ->add(Url::create('/sitemap-blogs.xml')->setLastModificationDate($blogLastUpdatedAt))
                ->add(Url::create('/sitemap-users.xml')->setLastModificationDate($userLastUpdatedAt));
            $routes = [
                'index' => $postLastUpdatedAt,
                'search' => $postLastUpdatedAt,
                'categories' => $categoryLastUpdatedAt,
                'faq' => $staticPagesUpdatedAt,
                'terms' => $staticPagesUpdatedAt,
                'privacy' => $staticPagesUpdatedAt,
                'site-map' => $staticPagesUpdatedAt,
                'blog.index' => $blogLastUpdatedAt,
                'feedbacks.create' => $staticPagesUpdatedAt,
                'about' => $aboutUsUpdatedAt,
                // 'plans.index' => $staticPagesUpdatedAt
            ];
            foreach ($routes as $route => $lastModDate) {
                $sitemap = $this->addLocalizedRoutes($sitemap, route($route), $lastModDate);
            }
            $sitemap->writeToFile($paths['master']);

            // posts sitemap
            $sm = Sitemap::create();
            foreach (Post::visible()->get() as $p) {
                $sm = $this->addLocalizedRoutes($sm, route('posts.show', $p), $p->updated_at);
            }
            $sm->writeToFile($paths['posts']);

            // categories sitemap
            $sm = Sitemap::create();
            foreach (Category::active()->get() as $c) {
                if (!$c->postsAll()->visible()->count()) {
                    continue;
                }
                $sm = $this->addLocalizedRoutes($sm, $c->getUrl(), $c->updated_at);
            }
            $sm->writeToFile($paths['categories']);

            // blogs sitemap
            $sm = Sitemap::create();
            foreach (Blog::published()->get() as $b) {
                $sm = $this->addLocalizedRoutes($sm, route('blog.show', $b), $b->updated_at);
            }
            $sm->writeToFile($paths['blogs']);

            // users sitemap
            $sm = Sitemap::create();
            foreach (User::all() as $u) {
                $sm = $this->addLocalizedRoutes($sm, route('users.show', $u), $u->updated_at);
            }
            $sm->writeToFile($paths['users']);

            // remove useless tags
            foreach ($paths as $path) {
                // Read the file into a string
                $content = file_get_contents($path);
            
                // Remove lines containing '<priority>' using regex
                $content = preg_replace('/.*<priority>.*\n/', '', $content);
                $content = preg_replace('/.*<changefreq>.*\n/', '', $content);
            
                // Write the modified string back to the file
                file_put_contents($path, $content);
            }

        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . exceptionAsString($th));
        }

        return 0;
    }

    private function addLocalizedRoutes($sitemap, $route, $lastModAt)
    {
        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $route = \LaravelLocalization::getLocalizedURL($localeCode, $route);
            $sitemap->add(Url::create($route)->setLastModificationDate($lastModAt));
        }

        return $sitemap;
    }
}
