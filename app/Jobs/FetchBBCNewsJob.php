<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\BBCNewsService;
use Illuminate\Support\Facades\Log;

class FetchBBCNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(BBCNewsService $bbcNewsService)
    {
        try {
            $news = $bbcNewsService->constructNewsData();
            Log::info("Fetched BBC News", $news);

            // Optionally store news in the database
        } catch (\Exception $e) {
            Log::error("BBC News Fetch Failed: " . $e->getMessage());
        }
    }
}
