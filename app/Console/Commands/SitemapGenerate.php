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

            // master sitemap
            Sitemap::create()
                ->add(Url::create('/')->setLastModificationDate(now()))
                ->add(Url::create('/sitemap-posts.xml')->setLastModificationDate(now()))
                ->add(Url::create('/sitemap-categories.xml')->setLastModificationDate(now()))
                ->add(Url::create('/sitemap-blogs.xml')->setLastModificationDate(now()))
                ->add(Url::create('/sitemap-users.xml')->setLastModificationDate(now()))
                ->add(Url::create(route('search'))->setLastModificationDate(now()))
                ->add(Url::create(route('categories'))->setLastModificationDate(now()))
                ->add(Url::create(route('faq'))->setLastModificationDate(now()))
                ->add(Url::create(route('terms'))->setLastModificationDate(now()))
                ->add(Url::create(route('privacy'))->setLastModificationDate(now()))
                ->add(Url::create(route('site-map'))->setLastModificationDate(now()))
                ->add(Url::create('/blog')->setLastModificationDate(now()))
                ->add(Url::create(route('feedbacks.create'))->setLastModificationDate(now()))
                ->add(Url::create(route('about'))->setLastModificationDate(now()))
                ->add(Url::create(route('plans.index'))->setLastModificationDate(now()))
                ->writeToFile($paths['master']);

            // posts sitemap
            $sm = Sitemap::create();
            foreach (Post::visible()->get() as $p) {
                $sm->add(Url::create(route('posts.show', $p))->setLastModificationDate(now()));
            }
            $sm->writeToFile($paths['posts']);

            // categories sitemap
            $sm = Sitemap::create();
            foreach (Category::active()->get() as $c) {
                if (!$c->postsAll()->visible()->count()) {
                    continue;
                }
                $sm->add(Url::create($c->getUrl())->setLastModificationDate(now()));
            }
            $sm->writeToFile($paths['categories']);

            // blogs sitemap
            $sm = Sitemap::create();
            foreach (Blog::published()->get() as $b) {
                $sm->add(Url::create(route('blog.show', $b))->setLastModificationDate(now()));
            }
            $sm->writeToFile($paths['blogs']);

            // users sitemap
            $sm = Sitemap::create();
            foreach (User::all() as $u) {
                $sm->add(Url::create(route('users.show', $u))->setLastModificationDate(now()));
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
