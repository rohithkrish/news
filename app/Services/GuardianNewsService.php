<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\RateLimiterTrait;
use App\Traits\SavesArticles;

class GuardianNewsService
{
    use RateLimiterTrait; use SavesArticles;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('app.news.guardian.api_key');
        $this->baseUrl = config('app.news.guardian.base_url');
    }


    public function constructNewsData(int $limit = 10): array
    {
        $news = $this->fetchNews();
        $data = $this->fetchData($news);
        if (empty($data)) {
            return [];
        }

        $this->saveArticles($data, 'The Guardian');
        return [ 'data'=> $data,'source'=> 'The Guardian'];
    }

    /**
     * Fetch news articles from The Guardian API.
     *
     * @param int $limit Number of articles to fetch.
     * @return array
     */
    public function fetchNews(int $limit = 10): array
    {
        try {
            // Check rate limit before making the request
            // Implementing a simple rate limiter
            $cacheKey = 'guardian_api_rate_limited';
            $this->checkRateLimit($cacheKey, 60, 60);

            $response = Http::get($this->baseUrl, [
                'api-key' => $this->apiKey,
                'show-fields' => 'byline',
                'page-size' => $limit,
            ]);

            // Update rate limit status after the request
           

            if ($response->successful()) {
                return $response->json()['response']['results'] ?? [];
            }
            Log::error('Guardian API Error: ' . $response->status());
            return [];
        } catch (\Exception $e) {
            Log::error('Guardian API Exception: ' . $e->getMessage());
            return [];
        }
    }

    public function fetchData($news): array{
        $data = [];
        foreach ($news as $article) {
            $data[] = [
                'title' => $article['webTitle'],
                'url' => $article['webUrl'],
                'source' => 'The Guardian',
                'author' => $article['fields']['byline'] ?? 'Unknown',
                'published_at' => date('Y-m-d', strtotime($article['webPublicationDate'])),
                'section' => $article['sectionName'],
                'description' => $article['webTitle'], 
            ];
        }
        return $data;
    }
}
