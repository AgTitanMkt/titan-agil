<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\CopaProfitService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ColaboradoresController extends Controller
{
    public function metas(){
        $metasDiaria = [
            'facebook'  => 30000,
            'youtube'   => 30000,
            'native' => 15000,
        ];
        $metasSemanal = [
            'facebook'  => 210000,
            'youtube'   => 210000,
            'native' => 105000,
        ];

        $metaQuinzenal = 1050000;

        $metrics = new CopaProfitService(Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth())->getPlatformsMetricsGroup();
        return view('colaboradores.metas',compact(['metasSemanal','metasDiaria','metaQuinzenal','metrics'])); // metas da Copa Profit
    }

}