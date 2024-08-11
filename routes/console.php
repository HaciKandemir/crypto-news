<?php

use App\Console\Commands\GetCryptoPanicNews;
use Illuminate\Support\Facades\Schedule;

/**
 * Fetch and store CryptoPanic news in Redis
 */
Schedule::command(GetCryptoPanicNews::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/get-crypto-panic-news.log'));
