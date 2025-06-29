<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\CleanupOldTasks;

Schedule::command(CleanupOldTasks::class)
    ->dailyAt('00:00') // Run at midnight
    ->runInBackground()
    ->withoutOverlapping();
