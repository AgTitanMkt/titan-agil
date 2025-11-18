<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('sync:redtrack --from="'.Carbon::now()->format('Y-m-d').'" --to="'.Carbon::now()->format('Y-m-d').'"')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->sendOutputTo(storage_path('logs/sync_redtrack.log'));

