<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule the activities cleanup to run daily (every 24 hours)
Schedule::command('activities:cleanup --days=30')
    ->daily()
    ->description('Clean up old activities from dashboard (older than 30 days)')
    ->withoutOverlapping();

// Schedule the notifications cleanup to run daily
// - Delete read notifications older than 2 days
// - Delete unread notifications older than 30 days
Schedule::command('notifications:cleanup --days=2 --unread-days=30')
    ->daily()
    ->description('Clean up read notifications older than 2 days and unread notifications older than 30 days')
    ->withoutOverlapping();
