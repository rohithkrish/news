<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\FetchGuardianNewsJob;
use App\Jobs\FetchNYTimesNewsJob;
use App\Jobs\FetchBBCNewsJob;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->job(new FetchGuardianNewsJob() )->daily();
         $schedule->job(new FetchBBCNewsJob() )->daily();
         $schedule->job(new FetchNYTimesNewsJob() )->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
   