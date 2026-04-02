<?php

namespace App\Jobs;

use App\Services\Dashboard\CopaProfitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // 🔥 AQUI

class GenerateCopaProfitCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $timeout = 600;
    public $tries = 1;

    public function handle()
    {
        Log::info('Iniciando geração de cache CopaProfit');

        $service = new CopaProfitService(null, null);
        $service->make();

        Cache::forget('copa_profit_generating');

        Log::info('Finalizado cache CopaProfit');
    }
}