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

            // master sitemap
            Sitemap::create()
                ->add(Url::create('/')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(1.0))
                ->add(Url::create('/sitemap-posts.xml')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9))
                ->add(Url::create('/sitemap-categories.xml')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9))
                ->add(Url::create('/sitemap-blogs.xml')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6))
                ->add(Url::create(route('search'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.9))
                ->add(Url::create(route('categories'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('faq'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('terms'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('privacy'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('site-map'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create('/blog')
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('feedbacks.create'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('about'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('import-rules'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8))
                ->add(Url::create(route('plans.index'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7))
                ->writeToFile(public_path('sitemap.xml'));

            // posts sitemap
            $sm = Sitemap::create();
            foreach (Post::visible()->get() as $p) {
                $sm->add(Url::create(route('posts.show', $p))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9));
            }
            $sm->writeToFile(public_path('sitemap-posts.xml'));

            // categories sitemap
            $sm = Sitemap::create();
            foreach (Category::active()->get() as $c) {
                $sm->add(Url::create($c->getUrl())
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8));
            }
            $sm->writeToFile(public_path('sitemap-categories.xml'));

            // blogs sitemap
            $sm = Sitemap::create();
            foreach (Blog::published()->get() as $b) {
                $sm->add(Url::create(route('blog.show', $b))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6));
            }
            $sm->writeToFile(public_path('sitemap-blogs.xml'));

        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . $th->getMessage(), [
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
        }
    }
}
