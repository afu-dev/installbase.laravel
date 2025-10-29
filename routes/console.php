<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Create scan every Sunday at 10:00 AM
Schedule::command('scan:create')
    ->weekly()
    ->sundays()
    ->at('10:00');

// Process executions every minute on Sundays starting at 10:01
Schedule::command('execution:work')
    ->everyMinute()
    ->sundays()
    ->when(function () {
        $now = now();
        return $now->hour >= 10 && ($now->hour > 10 || $now->minute >= 1);
    })
    ->withoutOverlapping();
