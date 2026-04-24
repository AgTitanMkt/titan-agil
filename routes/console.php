<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// roda a cada 10 minutos buscando dados de HOJE
Schedule::command('app:sync-redtrack', [
    '--from' => Carbon::now('America/Sao_Paulo')->format('Y-m-d'),
    '--to'   => Carbon::now('America/Sao_Paulo')->format('Y-m-d'),
])
    ->everyTenMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sync_redtrack.log'));

// roda 1x por dia buscando ontem (para fechar dia anterior) 
Schedule::command('app:sync-redtrack', [
    '--from' => Carbon::yesterday('America/Sao_Paulo')->format('Y-m-d'),
    '--to'   => Carbon::yesterday('America/Sao_Paulo')->format('Y-m-d'),
])
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sync_redtrack.log'));

// validacao dos criativos
Schedule::command('creatives:validate')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/creatives_validate.log'));


    