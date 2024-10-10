<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsArticle;
use Carbon\Carbon;

class ScrapeNewsApi extends Command
{
    protected $signature = 'scrape:newsapi {--days=1}';
    protected $description = 'Scrape news articles from NewsAPI for specified sources';

    protected $sources = ['the-verge', 'wired', 'abc-news'];

    public function handle()
    {
        $days = $this->option('days') ?? 1;
        $apiKey = config('services.newsapi.api_key');
        $url = "https://newsapi.org/v2/everything";

        $fromDate = now()->subDays($days)->toDateString();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get($url, [
            'sources' => implode(',', $this->sources),
            'from' => $fromDate,
            'language' => 'en',
            'sortBy' => 'publishedAt',
        ]);

        if ($response->successful()) {
            $articles = $response->json()['articles'];

            $this->output->progressStart(count($articles));

            foreach ($articles as $article) {
                $this->processArticle($article);
                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("Articles from NewsAPI scraped successfully.");
        } else {
            $this->error('Failed to fetch data from NewsAPI.');
            $this->error('Response: ' . $response->body());
        }
    }

    private function processArticle($article)
    {
        NewsArticle::updateOrCreate(
            ['url' => $article['url']],
            [
                'source_name' => $article['source']['name'],
                'source_id' => $article['source']['id'],
                'author' => $article['author'],
                'title' => $article['title'],
                'abstract' => $article['description'],
                'content' => $article['content'],
                'url_to_image' => $article['urlToImage'],
                'published_at' => Carbon::parse($article['publishedAt']),
                'news_type' => 'article',
                'keywords' => json_encode([]),
                'section_name' => null,
                'word_count' => null,
                'document_type' => null,
            ]
        );
    }
}
