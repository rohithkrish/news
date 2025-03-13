<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\RateLimiterTrait;
use App\Traits\SavesArticles;

class NYTimesNewsService 
{
    use RateLimiterTrait;
    use SavesArticles;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey =config('app.news.nytimes.api_key');
        $this->baseUrl = config('app.news.nytimes.base_url');
    }


    public function constructNewsData(int $limit = 10): array
    {
        $news = $this->fetchNews();
        $data = $this->fetchData($news);
        if (empty($data)) {
            return [];
        }
        $this->saveArticles($data, 'NY Times');
        return [ 'data'=> $data,'source'=> 'NY Times'];
    }

    public function fetchNews(int $limit = 10): array
    {
        try {
        // Implementing a simple rate limiter
        $cacheKey = 'nytimes_api_rate_limit';
        $this->checkRateLimit($cacheKey, 60, 60);

        $response = Http::get($this->baseUrl, [
            'api-key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $data = $response->json()['results'] ?? [];
             return array_slice($data, 0, $limit); // Limit the results
        }
        Log::error('NY Times API Error: ' . $response->status());
        return [];
        
        } catch (\Exception $e) {
            Log::error('Guardian API Exception: ' . $e->getMessage());
            return [];
        }
    }

    public function fetchData(array $news): array
    {
        $data = [];
        foreach ($news as $article) {
            $data[] = [
                'title' => $article['title'],
                'url' => $article['url'],
                'source' => 'NY Times',
                'author' => ltrim($article['byline'], ' By '),
                'published_at' => date('Y-m-d', strtotime($article['published_date'])),
                'section' => $article['section'],
                'description' => $article['abstract'], 
            ];
        }

        return $data;
    }
}
