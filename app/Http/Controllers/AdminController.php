<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalClicks = DB::table('vw_creatives_performance')->sum('clicks');
        $totalConversions = DB::table('vw_creatives_performance')->sum('conversions');
        $totalCost = DB::table('vw_creatives_performance')->sum('cost');
        $totalProfit = DB::table('vw_creatives_performance')->sum('profit');
        $avgRoi = DB::table('vw_creatives_performance')->avg('roi');

        $performanceByAgent = DB::table('vw_creatives_performance')
            ->select(
                'agent_name',
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(conversions) as conversions'),
                DB::raw('SUM(cost) as cost'),
                DB::raw('SUM(profit) as profit'),
                DB::raw('ROUND(SUM(profit)/NULLIF(SUM(cost),0), 2) as roas'),
                DB::raw('ROUND(AVG(roi), 2) as roi')
            )
            ->groupBy('agent_name')
            ->orderByDesc('profit')
            ->get();

        $performanceBySource = DB::table('vw_creatives_performance')
            ->select(
                'source',
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(conversions) as conversions'),
                DB::raw('SUM(cost) as cost'),
                DB::raw('SUM(profit) as profit'),
                DB::raw('ROUND(SUM(profit)/NULLIF(SUM(cost),0), 2) as roas'),
                DB::raw('ROUND(AVG(roi), 2) as roi')
            )
            ->groupBy('source')
            ->orderByDesc('profit')
            ->get();

        $topCreatives = DB::table('vw_creatives_performance')
            ->select(
                'creative_code',
                'source',
                DB::raw('GROUP_CONCAT(DISTINCT origem SEPARATOR ", ") AS origem'),
                DB::raw('GROUP_CONCAT(DISTINCT agent_name SEPARATOR " | ") AS agent_names'),
                DB::raw('SUM(clicks) AS clicks'),
                DB::raw('SUM(conversions) AS conversions'),
                DB::raw('SUM(cost) AS cost'),
                DB::raw('SUM(profit) AS profit'),
                DB::raw('ROUND(AVG(roi), 4) AS roi')
            )
            ->groupBy('creative_code','source')
            ->orderByDesc('profit')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalClicks',
            'totalConversions',
            'totalCost',
            'totalProfit',
            'avgRoi',
            'performanceByAgent',
            'performanceBySource',
            'topCreatives'
        ));
    }

    public function copywriters()
    {
        return view('admin.copy');
    }
}
