<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule the activities cleanup to run monthly (every 30 days)
Schedule::command('activities:cleanup --days=30')
    ->monthly()
    ->description('Clean up old activities from dashboard')
    ->withoutOverlapping();

// Schedule the notifications cleanup to run daily (delete read notifications older than 2 days)
Schedule::command('notifications:cleanup --days=2')
    ->daily()
    ->description('Clean up read notifications older than 2 days')
    ->withoutOverlapping();
