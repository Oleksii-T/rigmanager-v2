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
    private $minViews = 0;
    private $maxViews = 0;
    private $periodDays = 0;
    private $periodPastDays = 0;

    // php artisan posts:populate -S50 -M30 -W50 -P7 -D30 -Uaires@cepai.com

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:populate
                            {--S|skip-with-views=1 : Posts with this amount of views will be skipped }
                            {--M|min-views=15 : Minimum fake views to be created }
                            {--W|max-views=30 : Maximum fake views to be created }
                            {--P|period-past-days=0 : Minimum days past now for post date }
                            {--D|period-days=30 : Maximum days past now for post date }
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
        $f = 'Y-m-d';

        $this->warn("User: #$user->id $user->name <$user->email>");
        $this->warn("Amount of posts to be processed: " . $posts->count());
        $this->warn("Skip with Views: $this->skipWithViews");
        $this->warn("Amount of views to be faked: $this->minViews - $this->maxViews");
        $dText = now()->subDays($this->periodPastDays + $this->periodDays)->format($f) . ' - ' . now()->subDays($this->periodPastDays)->format($f);
        $this->warn("Date to be randomized: $dText");

        if ($posts->isEmpty()) {
            $this->error("No posts found");
            die();
        }

        if (!$this->confirm('Please confirm config above')) {
            return;
        }

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        $randDates = $this->randomizedDates(
            $posts->count(), 
            now()->subDays($this->periodPastDays + $this->periodDays), 
            now()->subDays($this->periodPastDays)
        );

        foreach ($posts as $i => $post) {
            $date = $randDates[$i];

            // randomize date
            $post->created_at = $date;
            $post->updated_at = $date;
            $post->save();

            // randomize views
            $viewsCount = rand($this->minViews, $this->maxViews);
            $randDatesForViews = $this->randomizedDates(
                $viewsCount, 
                $post->created_at,
                now()->subDays($this->periodPastDays), 
            );
            for ($i=0; $i < $viewsCount; $i++) {
                $date = $randDatesForViews[$i];
                $view = $post->saveView(true);
                $view->created_at = $date;
                $view->updated_at = $date;
                $view->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Process finished');
    }

    private function randomizedDates($count, $from, $to)
    {
        $randDates = [];
        $diffInDays = $from->diffInDays($to);
        $diffInMinutes = $diffInDays * 1440;

        for ($i=0; $i < $count; $i++) { 
            $randDates[] = (clone $to)->subMinutes(rand(0, $diffInMinutes));
        }

        sort($randDates);

        return $randDates;
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
            ->withCount([
                'activities' => fn ($q) => $q->where('event', 'view') 
            ])
            ->having('activities_count', '<', $skipWithViews)
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
        $this->periodPastDays = $this->option('period-past-days');
        $this->periodDays = $this->option('period-days');

        if (!$this->userId) {
            $this->userId = $this->ask('Please specify user id or email');
        }
    }
}
