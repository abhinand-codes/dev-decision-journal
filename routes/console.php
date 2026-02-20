<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Commands
|--------------------------------------------------------------------------
|
| In Laravel 12 the scheduler is registered directly in routes/console.php
| using the Schedule facade. This runs outside the HTTP request lifecycle,
| triggered by a single cron entry:  * * * * * php artisan schedule:run
|
*/

Schedule::command('decisions:mark-awaiting-review')->dailyAt('00:05');
