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
