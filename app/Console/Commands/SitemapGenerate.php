<?php

namespace App\Console\Commands;

use Spatie\Sitemap\SitemapGenerator;
use Log;
use Illuminate\Console\Command;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Sitemap;
use App\Models\Category;
use App\Models\Post;
use App\Models\Blog;
use App\Models\User;

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
            $staticPagesUpdatedAt = \Carbon\Carbon::parse('01/01/2024'); // m/d/y
            $aboutUsUpdatedAt = \Carbon\Carbon::parse('07/05/2024');

            // master sitemap
            Sitemap::create()
                ->add(Url::create('/')->setLastModificationDate($postLastUpdatedAt))
                ->add(Url::create('/sitemap-posts.xml')->setLastModificationDate($postLastUpdatedAt))
                ->add(Url::create('/sitemap-categories.xml')->setLastModificationDate($categoryLastUpdatedAt))
                ->add(Url::create('/sitemap-blogs.xml')->setLastModificationDate($blogLastUpdatedAt))
                ->add(Url::create('/sitemap-users.xml')->setLastModificationDate($userLastUpdatedAt))
                ->add(Url::create(route('search'))->setLastModificationDate($postLastUpdatedAt))
                ->add(Url::create(route('categories'))->setLastModificationDate($categoryLastUpdatedAt))
                ->add(Url::create(route('faq'))->setLastModificationDate($staticPagesUpdatedAt))
                ->add(Url::create(route('terms'))->setLastModificationDate($staticPagesUpdatedAt))
                ->add(Url::create(route('privacy'))->setLastModificationDate($staticPagesUpdatedAt))
                ->add(Url::create(route('site-map'))->setLastModificationDate($staticPagesUpdatedAt))
                ->add(Url::create('/blog')->setLastModificationDate($blogLastUpdatedAt))
                ->add(Url::create(route('feedbacks.create'))->setLastModificationDate($staticPagesUpdatedAt))
                ->add(Url::create(route('about'))->setLastModificationDate($aboutUsUpdatedAt))
                // ->add(Url::create(route('plans.index'))->setLastModificationDate($staticPagesUpdatedAt))
                ->writeToFile($paths['master']);

            // posts sitemap
            $sm = Sitemap::create();
            foreach (Post::visible()->get() as $p) {
                $sm->add(Url::create(route('posts.show', $p))->setLastModificationDate($p->updated_at));
            }
            $sm->writeToFile($paths['posts']);

            // categories sitemap
            $sm = Sitemap::create();
            foreach (Category::active()->get() as $c) {
                if (!$c->postsAll()->visible()->count()) {
                    continue;
                }
                $sm->add(Url::create($c->getUrl())->setLastModificationDate($c->updated_at));
            }
            $sm->writeToFile($paths['categories']);

            // blogs sitemap
            $sm = Sitemap::create();
            foreach (Blog::published()->get() as $b) {
                $sm->add(Url::create(route('blog.show', $b))->setLastModificationDate($b->updated_at));
            }
            $sm->writeToFile($paths['blogs']);

            // users sitemap
            $sm = Sitemap::create();
            foreach (User::all() as $u) {
                $sm->add(Url::create(route('users.show', $u))->setLastModificationDate($u->updated_at));
            }
            $sm->writeToFile($paths['users']);

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
}
