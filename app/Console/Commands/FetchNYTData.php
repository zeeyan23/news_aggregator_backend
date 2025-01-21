<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NYTimesModel;

class FetchNYTData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nytData:nytDataFetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $apiUrl = 'https://api.nytimes.com/svc/topstories/v2/world.json?api-key=Fs48mrLkrMgM3kSlEdIddIRszkIRQVhM'; // Replace with your actual API URL

        // Fetch data from the API
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $apiData = $response->json();

            if (isset($apiData['results']) && is_array($apiData['results'])) {
                foreach ($apiData['results'] as $result) {
                    // Transform API data to match database structure
                    $mappedData = [
                        'section' => $result['section'] ?? null,
                        'subsection' => $result['subsection'] ?? null,
                        'title' => $result['title'] ?? null,
                        'abstract' => $result['abstract'] ?? null,
                        'url' => $result['url'] ?? null,
                        'uri' => $result['uri'] ?? null,
                        'byline' => $result['byline'] ?? null,
                        'item_type' => $result['item_type'] ?? null,
                        'published_date' => isset($result['published_date']) 
                            ? \Carbon\Carbon::parse($result['published_date'])->toDateString() 
                            : null,
                        'imageUrl' => null
                        
                    ];
                    if (isset($result['multimedia']) && !empty($result['multimedia'])) {
                        // Get the first object from the multimedia array
                        $firstMultimedia = $result['multimedia'][0];
                    
                        // Check if the 'url' key exists in the first object
                        if (isset($firstMultimedia['url'])) {
                            $mappedData['imageUrl'] = $firstMultimedia['url'];
                        }
                    }
                    // Insert or update the data in your database
                    \App\Models\NYTimesModel::updateOrCreate(
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
