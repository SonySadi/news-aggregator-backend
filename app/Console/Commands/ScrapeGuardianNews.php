<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsArticle;
use Carbon\Carbon;

class ScrapeGuardianNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:guardian-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news articles from The Guardian API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.guardian.api_key');
        $url = "https://content.guardianapis.com/search";

        $response = Http::get($url, [
            'api-key' => $apiKey,
            'show-fields' => 'all',
            'page-size' => 50, // Adjust as needed
        ]);

        if ($response->successful()) {
            $articles = $response->json()['response']['results'];

            foreach ($articles as $article) {
                NewsArticle::updateOrCreate(
                    ['url' => $article['webUrl']],
                    [
                        'source_name' => 'The Guardian',
                        'source_id' => 'the-guardian',
                        'author' => $article['fields']['byline'] ?? null,
                        'title' => $article['webTitle'],
                        'abstract' => $article['fields']['trailText'] ?? null,
                        'content' => $article['fields']['bodyText'] ?? null,
                        'url_to_image' => $article['fields']['thumbnail'] ?? null,
                        'published_at' => Carbon::parse($article['webPublicationDate']),
                        'keywords' => json_encode($article['tags'] ?? []),
                        'section_name' => $article['sectionName'],
                        'pillar_name' => $article['pillarName'],
                        // Add other fields as needed
                    ]
                );
            }

            $this->info('Guardian news articles scraped successfully.');
        } else {
            $this->error('Failed to fetch data from The Guardian API.');
        }
    }
}
