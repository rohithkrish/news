<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NYTimesNewsService;
use Illuminate\Support\Facades\Log;

class FetchNYTimesNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NYTimesNewsService $nyTimesNewsService)
    {
        try {
            $news = $nyTimesNewsService->constructNewsData();
            Log::info("Fetched NYTimes News", $news);

            // Optionally store news in the database
        } catch (\Exception $e) {
            Log::error("NYTimes News Fetch Failed: " . $e->getMessage());
        }
    }
}
