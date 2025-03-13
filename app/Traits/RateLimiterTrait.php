<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait RateLimiterTrait
{
    /**
     * Apply rate limiting for an API.
     *
     * @param string $cacheKey - Unique cache key for tracking requests
     * @param int $rateLimit - Maximum allowed requests in the time window
     * @param int $timeWindow - Time window in seconds
     * @throws \Exception - If the rate limit is exceeded
     */
    public function checkRateLimit(string $cacheKey, int $rateLimit = 60, int $timeWindow = 60)
    {
        $requests = Cache::get($cacheKey, 0);

        if ($requests >= $rateLimit) {
            throw new \Exception('Rate limit exceeded. Try again later.');
        }

        // Increment request count and set expiration
        Cache::put($cacheKey, $requests + 1, $timeWindow);
    }
}
