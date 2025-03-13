<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\GuardianNewsService;
use Illuminate\Support\Facades\Log;

class FetchGuardianNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(GuardianNewsService $guardianNewsService)
    {
        try {
            $news = $guardianNewsService->constructNewsData();
            Log::info("Fetched Guardian News", $news);

            // Optionally store news in the database
            // News::insert($news);
        } catch (\Exception $e) {
            Log::error("Guardian News Fetch Failed: " . $e->getMessage());
        }
    }
}
