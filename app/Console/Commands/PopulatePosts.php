<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class PopulatePosts extends Command implements PromptsForMissingInput
{
    private $userId = false;
    private $skipWithViews = false;
    private $minViews = false;
    private $maxViews = false;
    private $minDaysPastNow = false;
    private $maxDaysPastNow = false;
    private $withoutDate = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:populate
                            {--skip-with-views=1 : Posts with this amount of views will be skipped }
                            {--min-views=15 : Minimum fake views to be created }
                            {--max-views=30 : Maximum fake views to be created }
                            {--min-days-from-now=0 : Minimum days past now for post date }
                            {--max-days-from-now=30 : Maximum days past now for post date }
                            {--without-days : Whether post dates should be randomized }
                            {--U|user= : User id or email whose posts to process }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randimize created_at date and create fake posts views';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setOptions();

        $user = $this->getUser($this->userId);
        $posts = $this->getPosts($user, $this->skipWithViews);

        $this->warn("User: #$user->id $user->name <$user->email>");
        $this->warn("Amount of posts to be processed: " . $posts->count());
        $this->warn("Skip with Views: $this->skipWithViews");
        $this->warn("Amount of views to be faked: $this->minViews - $this->maxViews");
        $dText = now()->subDays($this->maxDaysPastNow)->format('d/m/Y') . ' - ' . now()->subDays($this->minDaysPastNow)->format('d/m/Y');
        $this->warn("Date to be randomized: " . ($this->withoutDate ? '-' : $dText));

        if ($posts->isEmpty()) {
            $this->error("No posts found");
            die();
        }

        if (!$this->confirm('Please confirm config above')) {
            return;
        }

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        foreach ($posts as $post) {
            $this->randomizeViews($post);

            $this->randomizeDate($post);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Process finished');
    }

    private function randomizeViews($post)
    {
        $views = rand($this->minViews, $this->maxViews);
        for ($i=0; $i < $views; $i++) {
            $view = $post->views()->create([
                'user_id' => null,
                'ip' => fake()->ipv4(),
                'is_fake' => true,
            ]);

            if ($this->withoutDate) {
                continue;
            }

            $date = $this->randDate();
            $view->created_at = $date;
            $view->updated_at = $date;
            $view->save();
        }
    }

    private function randomizeDate($post)
    {
        if ($this->withoutDate) {
            return;
        }
        $date = $this->randDate();
        $post->created_at = $date;
        $post->updated_at = $date;
        $post->save();
    }

    private function randDate()
    {
        return now()->subDays(rand($this->minDaysPastNow, $this->maxDaysPastNow));
    }

    private function getUser($userId)
    {
        $user = intval($userId) == $userId
            ? User::find($userId)
            : User::where('email', $userId)->first();

        if (!$user) {
            $this->error("User '$userId' not found");
            die();
        }

        return $user;
    }

    private function getPosts($user, $skipWithViews)
    {
        $posts = Post::query()
            ->where('user_id', 9)
            ->withCount('views')
            ->having('views_count', '<', $skipWithViews)
            ->where('user_id', $user->id)
            ->get();

        return $posts;
    }

    private function setOptions()
    {
        $this->userId = $this->option('user');
        $this->skipWithViews = $this->option('skip-with-views');
        $this->minViews = $this->option('min-views');
        $this->maxViews = $this->option('max-views');
        $this->minDaysPastNow = $this->option('min-days-from-now');
        $this->maxDaysPastNow = $this->option('max-days-from-now');
        $this->withoutDate = $this->option('without-days');

        if (!$this->userId) {
            $this->userId = $this->ask('Please specify user id or email');
        }
    }
}
