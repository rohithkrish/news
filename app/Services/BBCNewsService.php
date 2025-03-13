<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\RateLimiterTrait;

class BBCNewsService {
    use RateLimiterTrait;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey =  env('BBC_API_KEY');
        $this->baseUrl = 'https://newsapi.org/v2/top-headlines?sources=bbc-news';
    }


    public function constructNewsData(int $limit = 10): array
    {
        $news = $this->fetchNews();
        $data = $this->fetchData($news);
        if (empty($data)) {
            return [];
        }

        return [ 'data'=> $data,'source'=> 'The Guardian'];
    }
    public function fetchNews(int $limit = 10): array
    {
        try {
            // Check rate limit before making the request
            // Implementing a simple rate limiter
            $cacheKey = 'bbc_api_rate_limited';
            $this->checkRateLimit($cacheKey, 60, 60);

            $response = Http::get($this->baseUrl, [
                'api-key' => $this->apiKey,
                'show-fields' => 'byline',
                'page-size' => $limit,
            ]);

            // Update rate limit status after the request
           

            if ($response->successful()) {
                return  array_slice($response->json()['articles'] ?? [], 0, $limit) ;
            }
            Log::error('BBC API Error: ' . $response->status());
            return [];
        } catch (\Exception $e) {
            Log::error('BBC API Exception: ' . $e->getMessage());
            return [];
        }
    }

    public function fetchData($news): array{
        $data = [];
        foreach ($news as $article) {
            $data[] = [
                'title' => $article['title'],
                'url' => $article['url'],
                'source' => 'NY Times',
                'author' =>$article['author'],
                'published_at' => date('Y-m-d', strtotime($article['publishedAt'])),
                'section' => 'general',
                'description' => $article['description'], 
            ];
        }
        return $data;
    }   
}
