<?php

namespace App\Jobs;

use App\Models\Scraper;
use App\Models\ScraperRun;
use App\Sanitizer\Sanitizer;
use Illuminate\Bus\Queueable;
use App\Enums\ScraperRunStatus;
use App\Enums\ScraperPostStatus;
use App\Services\PostScraperService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ScraperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $runModel;

    public function __construct(ScraperRun $runModel)
    {
        $this->runModel = $runModel;
    }

    public function handle()
    {
        try {
            $run = $this->runModel;

            // mark run as started
            $run->update([
                'status' => ScraperRunStatus::IN_PROGRESS
            ]);

            $this->log('Start Count...');

            // count posts to be scraped
            $postsCount = $this->countPostsToBeScraped();

            $this->log('Count Result', $postsCount);

            if ($run->only_count) {
                $run->update([
                    'max' => $postsCount,
                    'status' => ScraperRunStatus::SUCCESS,
                    'end_at' => now()
                ]);
                return;
            }

            // save progress
            $run->update([
                'scraped' => 0,
                'max' => $postsCount,
            ]);

            $this->log('Start Scrapping...');

            // scrape posts
            $scrapedPosts = $this->scrapePosts();

            $this->log('Posts Scrapped');
            $this->log('Start Save to DB...');

            // saving to db
            $this->saveScrapedPostsToDb($scrapedPosts);

            $this->log('Scraped Posts Saved to DB');

            $run->update([
                'status' => ScraperRunStatus::SUCCESS,
                'end_at' => now()
            ]);

        } catch (\Throwable $th) {

            $run->update([
                'status' => ScraperRunStatus::ERROR,
                'end_at' => now()
            ]);

            $run->logs()->create([
                'text' => 'ERROR: ' . $th->getMessage(),
                'data' => $th->getTraceAsString()
            ]);

            throw $th;
        }
    }

    private function countPostsToBeScraped() : int
    {
        $config = $this->runModel->scraper;
        $count = 0;
        foreach ($config['base_urls'] as $url) {
            $count += PostScraperService::make($url)
                ->post($config->post_selector ?? '')
                ->postLink($config->post_link_selector ?? '')
                ->pagination($config->pagination_selector ?? '')
                ->category($config->category_selector ?? '')
                ->sleep($config->sleep ?? 0)
                ->ignorePageError($this->runModel->ignore_page_error)
                ->onlyCount()
                ->scrape()['posts'];
        }

        return $count;
    }

    private function scrapePosts() : array
    {
        $run = $this->runModel;
        $config = $run->scraper;
        $result = [];

        foreach ($config['base_urls'] as $url) {
            $scrapper = PostScraperService::make($url)
                ->post($config->post_selector ?? '')
                ->postLink($config->post_link_selector ?? '')
                ->pagination($config->pagination_selector ?? '')
                ->category($config->category_selector ?? '')
                ->debug($run->scraper_debug_enabled)
                ->sleep($config->sleep ?? 0)
                ->limit($run->scrape_limit ?? 0)
                ->ignorePageError($run->ignore_page_error)
                ->afterEachScrape(function ($post) use ($run) {
                    $run->increment('scraped');
                })
                ->logUsing(function ($text) use ($run) {
                    $run->logs()->create([
                        'text' => $text
                    ]);
                });

            foreach ($config->selectors as $selector) {
                $scrapper->value(
                    $selector['name'],
                    $selector['value'],
                    $selector['attribute']??null,
                    $selector['is_multiple']??false,
                    $selector['from_posts_page']??false,
                    $selector['required']??false,
                );
            }

            $result = array_merge($result, $scrapper->scrape());
        }

        return $result;
    }

    private function saveScrapedPostsToDb(array $scrapedData) : void
    {
        $sanitize = $this->runModel->sanitize_html;
        $htmlSelectors = array_filter($this->runModel->scraper->selectors, fn ($a) => ($a['attribute']??false) == 'html');
        $htmlSelectors = array_column($htmlSelectors, 'name');

        foreach ($scrapedData as $url => $scrapedPostData) {
            if ($sanitize) {
                $scrapedPostData = $this->sanitizeScrapedPostData($scrapedPostData, $htmlSelectors);
            }

            $this->runModel->posts()->create([
                'url' => $url,
                'status' => ScraperPostStatus::PENDING,
                'data' => $scrapedPostData
            ]);
        }
    }

    private function sanitizeScrapedPostData($scrapedPostData, $htmlSelectors)
    {
        foreach ($scrapedPostData as $key => &$dataItem) {
            if (!$dataItem || !in_array($key, $htmlSelectors)) {
                continue;
            }
            if (is_array($dataItem)) {
                foreach ($dataItem as $i => $di) {
                    $dataItem[$i] = $this->sanitize($dataItem[$i]);
                }
            } else {
                $dataItem = $this->sanitize($dataItem);
            }
        }

        return $scrapedPostData;
    }

    private function sanitize($html)
    {
        $html = Sanitizer::handle($html, false);
        $html = str_replace("\t", '', $html);
        $html = str_replace("\r\n", '', $html);
        $html = str_replace("\n", '', $html);
        $html = preg_replace('/\s+/', ' ', $html);

        return $html;
    }

    private function log(string $text, $data=[])
    {
        return $this->runModel->logs()->create([
            'text' => $text,
            'data' => $data ?: null
        ]);
    }

    private static function getEscapedChars()
    {
        return [
            ["\r\n", "\n", false], // ensure there all the same new lines symbo
            ["\t", ' ', false], // change tabs to space
            ['&Acirc;', '', false],
            ['&nbsp;', ' ', false],
            ["\u{A0}", ' ', false], // same as &nbsp;

            ['&acirc;&#128;&cent;', '- ', false],
            ['&atilde;&#128;&#129;', ', ', false],
            ['&iuml;&frac14;&#154;', ': ', false],
            ['&iuml;&frac12;&#158;', '~', false],
            ['&iuml;&frac14;&#155;', '; ', false],
            ['&atilde;&#128;&#130;', '. ', false],
            ['&acirc;&#128;&#157;', '"', false],
            ['&acirc;&#128;&sup3;', '" ', false], // inches
            ['&acirc;&#128;&#156;', '"', false],
            ['&acirc;&#128;&#153;', "'", false],  // apostrophy
            ['&amp;', '&', false],
            ['&iuml;&frac14;&#140;', ', ', false],
            ['&iuml;&frac14;&#136;', ' (', false],
            ['&iuml;&frac14;&#137;', ') ', false],
            ['&iuml;&frac14;&#141;', '-', false],
            ['&iuml;&frac14;&#139;', '+', false],
            ['&acirc;&#132;&#131;', 'C', false],
            ['&iuml;&frac14;&#156;', '<', false],
            ['&iuml;&#129;&not;', '', false],
            ['&atilde;&#128;&#157;', '"', false],
            ['&atilde;&#128;&#158;', '"', false],
            ['&acirc;&#129;&#132;', '/', false],
            ['&acirc;&#129;&#132;', '/', false],
            ['&middot;', '- ', false],
            ['&acirc;&#128;&#148;', '-', false],

            [chr(195).chr(131).chr(194).chr(151), 'x', false],
            [chr(195).chr(142).chr(194).chr(188), 'µ', false],
            [chr(195).chr(142).chr(194).chr(169), 'Ω', false],
            [chr(195).chr(143).chr(194).chr(134), 'φ', false],
            [chr(195).chr(131).chr(194).chr(184), 'ø', false],
            [chr(195).chr(142).chr(194).chr(166), 'Φ', false],
            [chr(195).chr(131).chr(194).chr(152), 'Ø', false],
            [chr(195).chr(142).chr(194).chr(148), 'Δ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(136), ' (', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(137), ') ', false],
            [chr(195).chr(162).chr(194).chr(137).chr(194).chr(164), '≤', false],
            [chr(195).chr(162).chr(194).chr(137).chr(194).chr(165), '≥', false],
            [chr(195).chr(162).chr(194).chr(128).chr(194).chr(147), '-', false],
            [chr(195).chr(162).chr(194).chr(132).chr(194).chr(131), '℃', false],
            [chr(195).chr(175).chr(194).chr(129).chr(194).chr(172), '', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(133), '%', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(133), '%', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(156), '<', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(141), '-', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(139), '+', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(140), ', ', false],
            [chr(195).chr(165).chr(194).chr(163).chr(194).chr(171), '±', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(179), '△', false],
            [chr(195).chr(142).chr(194).chr(148).chr(194).chr(176), 'Δ°', false],
            [chr(195).chr(175).chr(194).chr(185).chr(194).chr(163), '﹣', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(161), '', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(161), '', false],
            [chr(195).chr(162).chr(194).chr(136).chr(194).chr(163), '|', false],
            [chr(195).chr(175).chr(194).chr(185).chr(194).chr(159), '#', false],
            [chr(195).chr(163).chr(194).chr(128).chr(194).chr(158), '"', false],
            [chr(195).chr(162).chr(194).chr(133).chr(194).chr(161), 'Ⅱ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(142), '.', false],
            [chr(195).chr(162).chr(194).chr(133).chr(194).chr(162), 'Ⅲ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(158), '>', false],
            // [, false],


            ['/^[ \n]*/', "", true],  // remove leading newlines and spaces
            ['/ +\n/', "\n", true], // remove spaces before new lines
            ['/(\n){3,}/', "\n\n", true], // remove dublicated new lines
            ["\n", "\r\n", false], // make Windows friendly new lines
        ];
    }
}
