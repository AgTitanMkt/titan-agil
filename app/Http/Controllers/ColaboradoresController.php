<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\CopaProfitService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ColaboradoresController extends Controller
{
    public function metas()
    {
        $metasDiaria = [
            'facebook'  => 30000,
            'google'   => 30000,
            'native' => 15000,
        ];
        $metasSemanal = [
            'facebook'  => 210000,
            'google'   => 210000,
            'native' => 105000,
        ];

        $metaQuinzenal = 1050000;
        $metricasDiaria = new CopaProfitService(Carbon::now()->startOfDay(), Carbon::now()->endOfDay())->getPlatformsMetricsGroup();
        $metricasSemana = new CopaProfitService(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())->getPlatformsMetricsGroup();


        $agora = Carbon::now();

        // 📌 VERIFICA SE ESTAMOS NA 1ª OU 2ª QUINZENA
        if ($agora->day <= 15) {
            $inicio = $agora->copy()->startOfMonth();
            $fim    = $agora->copy();
        } else {
            $inicio = $agora->copy()->startOfMonth()->addDays(15);
            $fim    = $agora->copy();
        }

        $metricasQuinzenal = (new CopaProfitService(
            $inicio,
            $fim
        ))->getPlatformsMetricsGroup();

        $metricasDiariaSources  = (new CopaProfitService(Carbon::now()->startOfDay(),  Carbon::now()->endOfDay()))->getPlatformsMetricsSources();
        $metricasSemanaSources  = (new CopaProfitService(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()))->getPlatformsMetricsSources();
        $metricasQuinzSources   = (new CopaProfitService($inicio, $fim))->getPlatformsMetricsSources();

        $metricasDiariaSources = collect($metricasDiariaSources)->filter(function($data){
            return $data['total_profit'] > 0;
        });
        $metricasSemanaSources = collect($metricasSemanaSources)->filter(function($data){
            return $data['total_profit'] > 0;
        });
        // $metricasQuinzSources = collect($metricasQuinzSources)->filter(function($data){
        //     return $data['total_profit'] > 0;

        $metricasQuinzSources = collect($metricasQuinzSources)->sum('total_profit');
        // dd($metricasQuinzenal);

        $metrics = new CopaProfitService(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth())->getPlatformsMetricsGroup();
                
        return view('colaboradores.metas', compact([
            'metasSemanal',
            'metasDiaria',
            'metaQuinzenal',
            'metrics',
            'metricasDiaria',
            'metricasSemana',
            'metricasQuinzenal',
            'metricasDiariaSources',
            'metricasSemanaSources',
            'metricasQuinzSources',
        ]));
    }
}
