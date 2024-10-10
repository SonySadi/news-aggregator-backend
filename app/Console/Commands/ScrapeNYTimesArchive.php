<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsArticle;
use Carbon\Carbon;

class ScrapeNYTimesArchive extends Command
{
    protected $signature = 'scrape:nytimes-archive {year?} {month?}';
    protected $description = 'Scrape news articles from the NYTimes Archive API for a specific year and month (defaults to current month)';

    public function handle()
    {
        $year = $this->argument('year') ?? now()->year;
        $month = $this->argument('month') ?? now()->month;

        $apiKey = config('services.nytimes.api_key');
        $url = "https://api.nytimes.com/svc/archive/v1/{$year}/{$month}.json";

        $this->info("Fetching NYTimes articles for {$year}-{$month}...");

        $response = Http::get($url, [
            'api-key' => $apiKey,
        ]);

        if ($response->successful()) {
            $articles = $response->json()['response']['docs'];

            $this->output->progressStart(count($articles));

            foreach ($articles as $article) {
                $this->processArticle($article);
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("NYTimes articles for {$year}-{$month} scraped successfully.");
        } else {
            $this->error('Failed to fetch data from the NYTimes Archive API.');
            $this->error('Response: ' . $response->body());
        }
    }

    private function processArticle($article)
    {
        NewsArticle::updateOrCreate(
            ['url' => $article['web_url']],
            [
                'source_name' => 'The New York Times',
                'source_id' => 'nytimes',
                'author' => $article['byline']['original'] ?? null,
                'title' => $article['headline']['main'],
                'abstract' => $article['abstract'],
                'content' => $article['lead_paragraph'],
                'url_to_image' => $this->getImageUrl($article),
                'published_at' => Carbon::parse($article['pub_date']),
                'keywords' => json_encode($this->extractKeywords($article)),
                'section_name' => $article['section_name'],
                'news_type' => $article['type_of_material'] ?? null,
                'word_count' => $article['word_count'] ?? null,
                'document_type' => $article['document_type'],
            ]
        );
    }

    private function getImageUrl($article)
    {
        if (!empty($article['multimedia'])) {
            $image = collect($article['multimedia'])
                ->where('type', 'image')
                ->sortByDesc('width')
                ->first();

            if ($image) {
                return "https://www.nytimes.com/" . $image['url'];
            }
        }
        return null;
    }

    private function extractKeywords($article)
    {
        return collect($article['keywords'])
            ->pluck('value')
            ->toArray();
    }
}
