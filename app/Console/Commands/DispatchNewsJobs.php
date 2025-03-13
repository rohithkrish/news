<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchGuardianNewsJob;
use App\Jobs\FetchNYTimesNewsJob;
use App\Jobs\FetchBBCNewsJob;
use Illuminate\Support\Facades\Log;

class DispatchNewsJobs extends Command
{
    protected $signature = 'news:fetch';  // Command name to run
    protected $description = 'Dispatch jobs to fetch news from Guardian, NYTimes, and BBC';

    public function handle()
    {
        // Dispatch jobs to the queue
        dispatch(new FetchGuardianNewsJob());
        dispatch(new FetchNYTimesNewsJob());
        dispatch(new FetchBBCNewsJob());

        Log::info('News fetching jobs dispatched successfully.');

        $this->info('News fetching jobs dispatched to queue.');
    }
}
