<?php

use App\Console\Commands\GetCryptoPanicNews;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GetCryptoPanicNews::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/get-crypto-panic-news.log'));
