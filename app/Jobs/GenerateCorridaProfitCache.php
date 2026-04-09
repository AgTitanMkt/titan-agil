<?php

namespace App\Jobs;

use App\Services\Dashboard\CorridaProfitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * job dedicado para a Corrida do Profit 2026. Mas, nao ira alterar nada do GenerateCopaProfitCache, pois tem regras e metas diferentes.
 *
 * CACHE KEY: corrida_profit_{YYYY}
 * TTL: 10 MINUTOS (600 SEGUNDOS)
 */
class GenerateCorridaProfitCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $timeout = 600;
    public $tries   = 1;

    public function handle(): void
    {
        try {
            Log::info('[CorridaProfit] Iniciando geração de cache Corrida do Profit');

            $service = new CorridaProfitService();
            $data    = $service->make();

            // CACHE KEY ANUAL: corrida_profit_2026
            $cacheKey = 'corrida_profit_' . now()->year;

            Cache::put($cacheKey, $data, 600);

            // libera a flag de "gerando"
            Cache::forget('corrida_profit_generating');

            Log::info('[CorridaProfit] Cache gerado com sucesso', [
                'key'   => $cacheKey,
                'squads' => count($data['podium'] ?? []),
            ]);

        } catch (\Throwable $e) {
            Log::error('[CorridaProfit] ERRO no Job', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            // libera a flag mesmo com erro para nao travar na proxima tentativa
            Cache::forget('corrida_profit_generating');

            throw $e;
        }
    }
}