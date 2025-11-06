<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // 🔹 Listas para os selects
        $allCreatives = DB::table('vw_creatives_performance')
            ->select('creative_code', 'agent_name', 'user_id')
            ->distinct()
            ->pluck('creative_code')
            ->toArray();

        $allSources = DB::table('vw_creatives_performance')
            ->select('source')
            ->distinct()
            ->pluck('source')
            ->toArray();

        $allEditors = DB::table('vw_creatives_performance')
            ->select('agent_name')
            ->where('role_id', 3)
            ->distinct()
            ->pluck('agent_name')
            ->toArray();

        $allCopywriters = $this->allCopywritersArray();

        // 🔹 Base da query (sem executar ainda)
        $query = DB::table('vw_creatives_performance')
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
            ->groupBy('creative_code', 'source')
            ->orderByDesc('profit');

        // 🔹 Filtros cumulativos
        if ($request->filled('sources')) {
            $query->where('source', $request->sources);
        }

        if ($request->filled('creatives')) {
            $query->whereIn('creative_code', $request->creatives);
        }

        // 🔹 Executa a query (com filtros ou sem)
        $topCreatives = $query->paginate(15)->appends($request->query());

        return view('admin.dashboard', compact(
            'topCreatives',
            'allCreatives',
            'allSources',
            'allEditors',
            'allCopywriters',
        ));
    }

    public function copywriters(Request $request)
    {
        // Subquery: consolida cada criativo (sem duplicar por source)
        $sub = DB::table('vw_creatives_performance')
            ->select(
                'user_id',
                'agent_name',
                'creative_code',
                DB::raw('SUM(clicks) AS clicks'),
                DB::raw('SUM(conversions) AS conversions'),
                DB::raw('SUM(cost) AS cost'),
                DB::raw('SUM(profit) AS profit'),
                DB::raw('SUM(revenue) AS revenue'),
                DB::raw('AVG(roi) AS roi'),
                DB::raw('AVG(roas) AS roas'),
                DB::raw('AVG(ctr) AS ctr'),
                DB::raw('AVG(cpm) AS cpm')
            )
            ->where('role_id', 2) // copywriters
            ->groupBy('user_id', 'agent_name', 'creative_code');

        // Query principal: agrupa por copywriter e gera totais
        $baseQuery = DB::query()
            ->fromSub($sub, 't')
            ->select(
                'user_id',
                'agent_name',
                DB::raw('COUNT(creative_code) AS total_creatives'),
                DB::raw('SUM(clicks) AS total_clicks'),
                DB::raw('SUM(conversions) AS total_conversions'),
                DB::raw('SUM(cost) AS total_cost'),
                DB::raw('SUM(profit) AS total_profit'),
                DB::raw('SUM(revenue) AS total_revenue'),
                // ROI médio real (média do profit / média do cost)
                DB::raw('ROUND((AVG(profit) / NULLIF(AVG(cost), 0)), 4) AS avg_roi'),
                // ROI total consolidado (soma total do profit / soma total do cost)
                DB::raw('ROUND((SUM(profit) / NULLIF(SUM(cost), 0)), 4) AS total_roi'),
                DB::raw('ROUND(AVG(roas), 4) AS avg_roas'),
                DB::raw('ROUND(AVG(ctr), 4) AS avg_ctr'),
                DB::raw('ROUND(AVG(cpm), 4) AS avg_cpm')
            )
            ->groupBy('user_id', 'agent_name');


        $allCopywriters = $this->allCopywritersArray();

        // 🔹 Filtros cumulativos
        if ($request->filled('copywriters')) {
            $baseQuery->where('agent_name', $request->copywriters);
        }

        $copies = $baseQuery->orderByDesc('total_profit')
            ->paginate(20);

        return view('admin.copy', compact('copies', 'allCopywriters'));
    }


    public function allCopywritersArray()
    {
        return  DB::table('vw_creatives_performance')
            ->select('agent_name')
            ->where('role_id', 2)
            ->distinct()
            ->pluck('agent_name')
            ->toArray();
    }

    public function time(Request $request)
    {
        // Subquery consolidando criativos (sem duplicar por source)
        $sub = DB::table('vw_creatives_performance')
            ->select(
                'role_id',
                'role_name',
                'user_id',
                'agent_name',
                'creative_code',
                DB::raw('SUM(clicks) AS clicks'),
                DB::raw('SUM(conversions) AS conversions'),
                DB::raw('SUM(cost) AS cost'),
                DB::raw('SUM(profit) AS profit'),
                DB::raw('SUM(revenue) AS revenue'),
                DB::raw('AVG(roi) AS roi'),
                DB::raw('AVG(roas) AS roas'),
                DB::raw('AVG(ctr) AS ctr'),
                DB::raw('AVG(cpm) AS cpm')
            )
            ->groupBy('role_id', 'role_name', 'user_id', 'agent_name', 'creative_code');

        // Query principal: consolida por pessoa dentro do time
        $query = DB::query()
            ->fromSub($sub, 't')
            ->select(
                'role_id',
                'role_name',
                'user_id',
                'agent_name',
                DB::raw('COUNT(creative_code) AS total_creatives'),
                DB::raw('SUM(clicks) AS total_clicks'),
                DB::raw('SUM(conversions) AS total_conversions'),
                DB::raw('SUM(cost) AS total_cost'),
                DB::raw('SUM(profit) AS total_profit'),
                DB::raw('SUM(revenue) AS total_revenue'),
                DB::raw('ROUND((AVG(profit) / NULLIF(AVG(cost), 0)), 4) AS avg_roi'),
                DB::raw('ROUND((SUM(profit) / NULLIF(SUM(cost), 0)), 4) AS total_roi'),
                DB::raw('ROUND(AVG(roas), 4) AS avg_roas'),
                DB::raw('ROUND(AVG(ctr), 4) AS avg_ctr'),
                DB::raw('ROUND(AVG(cpm), 4) AS avg_cpm')
            )
            ->groupBy('role_id', 'role_name', 'user_id', 'agent_name');

        // 🔹 Filtros (opcionais)
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Executa a query e pagina
        $teamPerformance = $query
            ->orderByDesc('total_profit')
            ->paginate(30);

        // 🔹 Para dropdowns e filtros
        $roles = DB::table('roles')->select('id', 'title')->get();
        $users = User::select('id', 'name','email')->get();

        return view('admin.time', compact('teamPerformance', 'roles', 'users'));
    }

    public function faturamento(Request $request)
    {
        return view('admin.faturamento');
    }
}
