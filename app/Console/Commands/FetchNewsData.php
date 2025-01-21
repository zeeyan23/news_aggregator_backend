<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsModel; // Import your model here

class FetchNewsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news data from live API and insert into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // API endpoint
        $apiUrl = 'https://newsapi.org/v2/everything?domains=techcrunch.com,thenextweb.com&apiKey=10a752246e3445a3a4ef496cfc4b2f91'; // Replace with your actual API URL

        // Fetch data from the API
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $apiData = $response->json();

            if (isset($apiData['articles']) && is_array($apiData['articles'])) {
                foreach ($apiData['articles'] as $article) {
                    // Transform API data to match database structure
                    $mappedData = [
                        'source_name' => $article['source']['name'] ?? null,
                        'author' => $article['author'] ?? null,
                        'title' => $article['title'] ?? null,
                        'description' => $article['description'] ?? null,
                        'url' => $article['url'] ?? null,
                        'url_to_image' => $article['urlToImage'] ?? null,
                        'published_at' => isset($article['publishedAt']) 
                            ? \Carbon\Carbon::parse($article['publishedAt'])->toDateString() 
                            : null,
                        'content' => $article['content'] ?? null,
                    ];

                    // Insert or update the data in your database
                    \App\Models\NewsModel::updateOrCreate(
                        ['title' => $mappedData['title']], // Use title as unique identifier
                        $mappedData
                    );
                }

                $this->info('News data fetched, transformed, and inserted successfully!');
            } else {
                $this->warn('No articles found in the API response.');
            }
        } else {
            $this->error('Failed to fetch news data from the API. Please check the API URL and response.');
        }
    }

}
