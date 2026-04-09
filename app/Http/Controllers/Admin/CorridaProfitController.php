<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCorridaProfitCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * controller dedicado para a Corrida do Profit 2026.
 * usa cache com fallback via job —> mesma estrategia do Dashboard -> ADMINCONTROLLER.PHP onde existia o Copa Profit
 * Mas, nao ira alterar nada do AdminController -> Dashboard, pois tem regras e metas diferentes.
 */
class CorridaProfitController extends Controller
{
    public function corrida(Request $request)
{
    $cacheKey = 'corrida_profit_' . now()->year;

    $data = Cache::get($cacheKey);

    // se nao tem cache -> gera na hora
    if (!$data) {
        $service = new \App\Services\Dashboard\CorridaProfitService();
        $data = $service->make();

        // salva cache imediatamente
        Cache::put($cacheKey, $data, 600);

        // dispara job async pra atualizar depois 
        dispatch(new GenerateCorridaProfitCache());
    }

    return view('admin.corrida-profit.corrida', $data);
}

    /**
     * endpoint AJAX para atualizar o cache manualmente com button ATUALIZAR na view)
     */
    public function refresh(Request $request)
    {
        if (!Cache::has('corrida_profit_generating')) {
            Cache::put('corrida_profit_generating', true, 600);
            (new GenerateCorridaProfitCache())->handle();
        }

        return response()->json(['status' => 'queued']);
    }
}