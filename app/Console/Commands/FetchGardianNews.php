<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\GuardianModel; 

class FetchGardianNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gaurdianNews:gaurdianNewsFetch';

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
        $apiUrl = 'https://content.guardianapis.com/search?api-key=0cd153ef-8095-468f-b3ee-68a311bb25e8'; // Replace with your actual API URL

        // Fetch data from the API
        // $response = Http::get($apiUrl);
        $response = Http::timeout(30)->get($apiUrl);

        if ($response->successful()) {
            $apiData = $response->json();

            if (isset($apiData['response']['results']) && is_array($apiData['response']['results'])) {
                foreach ($apiData['response']['results'] as $result) {
                    // Transform API data to match database structure
                    $mappedData = [
                        'type' => $result['type'] ?? null,
                        'sectionId' => $result['sectionId'] ?? null,
                        'sectionName' => $result['sectionName'] ?? null,
                        'webPublicationDate' => isset($result['webPublicationDate']) 
                            ? \Carbon\Carbon::parse($result['webPublicationDate'])->toDateString() 
                            : null,
                        'webTitle' => $result['webTitle'] ?? null,
                        'webUrl' => $result['webUrl'] ?? null,
                        
                        'apiUrl' => $result['apiUrl'] ?? null,
                        'isHosted' => $result['isHosted'] ?? null,
                        'pillarId' => $result['pillarId'] ?? null,
                        'pillarName' => $result['pillarName'] ?? null,
                    ];

                    // Insert or update the data in your database
                    \App\Models\GuardianModel::updateOrCreate(
                        ['webTitle' => $mappedData['webTitle']], // Use title as unique identifier
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
