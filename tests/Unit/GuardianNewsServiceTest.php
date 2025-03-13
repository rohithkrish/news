<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GuardianNewsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Mockery;

class GuardianNewsServiceTest extends TestCase
{
    protected $guardianNewsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guardianNewsService = new GuardianNewsService();
    }

    public function testFetchNewsSuccessful()
    {
        // Mock the HTTP response
        Http::fake([
            '*' => Http::response([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => 'Test Article',
                            'webUrl' => 'https://example.com/test-article',
                            'fields' => ['byline' => 'John Doe'],
                            'webPublicationDate' => '2025-03-13T12:00:00Z',
                            'sectionName' => 'Technology',
                        ],
                    ],
                ],
            ], 200),
        ]);

        // Mock the rate limiter
        Cache::shouldReceive('get')
            ->with('guardian_api_rate_limited', 0)
            ->andReturn(0);
        Cache::shouldReceive('put')
            ->with('guardian_api_rate_limited', Mockery::any(), Mockery::any())
            ->andReturn(true);

        $news = $this->guardianNewsService->fetchNews(10);

        $this->assertNotEmpty($news);
        $this->assertEquals('Test Article', $news[0]['webTitle']);
    }

    public function testFetchNewsRateLimitExceeded()
    {
        // Mock the rate limiter
        Cache::shouldReceive('get')
            ->with('guardian_api_rate_limited', 0)
            ->andReturn(60);

        $news = $this->guardianNewsService->fetchNews(10);

        $this->assertEmpty($news);
    }

    public function testFetchNewsApiError()
    {
        // Mock the HTTP response
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        // Mock the rate limiter
        Cache::shouldReceive('get')
            ->with('guardian_api_rate_limited', 0)
            ->andReturn(0);
        Cache::shouldReceive('put')
            ->with('guardian_api_rate_limited', Mockery::any(), Mockery::any())
            ->andReturn(true);

        Log::shouldReceive('error')
            ->once()
            ->with('Guardian API Error: 500');

        $news = $this->guardianNewsService->fetchNews(10);

        $this->assertEmpty($news);
    }

    public function testFetchData()
    {
        $news = [
            [
                'webTitle' => 'Test Article',
                'webUrl' => 'https://example.com/test-article',
                'fields' => ['byline' => 'John Doe'],
                'webPublicationDate' => '2025-03-13T12:00:00Z',
                'sectionName' => 'Technology',
            ],
        ];

        $data = $this->guardianNewsService->fetchData($news);

        $this->assertNotEmpty($data);
        $this->assertEquals('Test Article', $data[0]['title']);
        $this->assertEquals('https://example.com/test-article', $data[0]['url']);
        $this->assertEquals('The Guardian', $data[0]['source']);
        $this->assertEquals('John Doe', $data[0]['author']);
        $this->assertEquals('2025-03-13', $data[0]['published_at']);
        $this->assertEquals('Technology', $data[0]['section']);
        $this->assertEquals('Test Article', $data[0]['description']);
    }
}