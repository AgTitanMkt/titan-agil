<?php

namespace App\Http\Controllers;

use App\Models\RedtrackReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function faturamento(Request $request)
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


    public function editors(Request $request)
    {
        // -----------------------------------------
        // 1️⃣ Intervalo de datas
        // -----------------------------------------
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // -----------------------------------------
        // 2️⃣ Lista completa de Editores (role_id = 3)
        // -----------------------------------------
        $allEditors = DB::table('users AS u')
            ->join('user_roles AS ur', 'ur.user_id', '=', 'u.id')
            ->where('ur.role_id', 3) // EDITORES
            ->pluck('u.name', 'u.name')
            ->toArray();

        // -----------------------------------------
        // 3️⃣ Subquery sem perda de registros
        // -----------------------------------------
        $sub = DB::table('redtrack_reports AS rt')
            ->join('tasks AS t', 't.normalized_code', '=', 'rt.normalized_rt_ad')
            ->join('sub_tasks AS st', 'st.task_id', '=', 't.id')
            ->join('user_tasks AS ut', 'ut.sub_task_id', '=', 'st.id')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('user_roles AS ur', 'ur.user_id', '=', 'u.id')
            ->where('ur.role_id', 3) // EDITORES
            ->whereBetween('rt.date', [$startDate, $endDate])
            ->select(
                'u.id AS user_id',
                'u.name AS editor_name',
                't.code AS creative_code',

                'rt.id AS redtrack_id',
                'rt.date AS redtrack_date',
                'rt.clicks AS rt_clicks',
                'rt.conversions AS rt_conversions',
                'rt.cost AS rt_cost',
                'rt.profit AS rt_profit',
                DB::raw('(rt.cost + rt.profit) AS rt_revenue')
            )
            ->groupBy(
                'u.id',
                'u.name',
                't.code',
                'rt.id',
                'rt.date',
                'rt.clicks',
                'rt.conversions',
                'rt.cost',
                'rt.profit'
            );

        // -----------------------------------------
        // 4️⃣ Consolidação por editor
        // -----------------------------------------
        $baseQuery = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'editor_name',
                DB::raw('COUNT(DISTINCT creative_code) AS total_creatives'),
                DB::raw('SUM(rt_clicks) AS total_clicks'),
                DB::raw('SUM(rt_conversions) AS total_conversions'),
                DB::raw('SUM(rt_cost) AS total_cost'),
                DB::raw('SUM(rt_profit) AS total_profit'),
                DB::raw('SUM(rt_revenue) AS total_revenue'),
                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS total_roi')
            )
            ->groupBy('user_id', 'editor_name');

        // -----------------------------------------
        // 5️⃣ Filtro multiselect de editores
        // -----------------------------------------
        if ($request->filled('editors')) {
            $baseQuery->whereIn('editor_name', (array) $request->editors);
        }

        $editors = $baseQuery->orderByDesc('total_profit')->paginate(20);

        // -----------------------------------------
        // 6️⃣ Criativos por editor (para expansão)
        // -----------------------------------------
        $creativesByEditor = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'creative_code',
                DB::raw('SUM(rt_clicks) AS clicks'),
                DB::raw('SUM(rt_conversions) AS conversions'),
                DB::raw('SUM(rt_cost) AS cost'),
                DB::raw('SUM(rt_profit) AS profit'),
                DB::raw('SUM(rt_revenue) AS revenue'),
                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS roi')
            )
            ->groupBy('user_id', 'creative_code')
            ->orderBy('profit', 'desc')
            ->get()
            ->groupBy('user_id');
        // -----------------------------------------
        // 7️⃣ Retorno
        // -----------------------------------------
        return view('admin.editors', compact(
            'editors',
            'allEditors',
            'startDate',
            'endDate',
            'creativesByEditor'
        ));
    }


    public function copywriters(Request $request)
    {
        // -----------------------------------------
        // 1️⃣ Intervalo de datas
        // -----------------------------------------
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();


        // -----------------------------------------
        // 2️⃣ Lista completa de copywriters (multiselect)
        // -----------------------------------------
        $allCopywriters = $this->allCopywritersArray();


        // -----------------------------------------
        // 3️⃣ SUBQUERY BASE
        //
        // Esta subquery agora é perfeita:
        //  ✔ Não perde registros
        //  ✔ Agrupa por RT.ID + Date (evita merge indevido)
        //  ✔ Mantém um registro por entrada do RedTrack
        //  ✔ Soma exata (ex: 1379.46 + 1087.50)
        // -----------------------------------------
        $sub = DB::table('redtrack_reports AS rt')
            ->join('tasks AS t', 't.normalized_code', '=', 'rt.normalized_rt_ad')
            ->join('sub_tasks AS st', 'st.task_id', '=', 't.id')
            ->join('user_tasks AS ut', 'ut.sub_task_id', '=', 'st.id')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('user_roles AS ur', 'ur.user_id', '=', 'u.id')
            ->where('ur.role_id', 2) // apenas copywriters
            ->whereBetween('rt.date', [$startDate, $endDate])
            ->select(
                'u.id AS user_id',
                'u.name AS agent_name',
                't.code AS creative_code',

                // cada linha real do RedTrack
                'rt.id AS redtrack_id',
                'rt.date AS redtrack_date',
                'rt.clicks AS rt_clicks',
                'rt.conversions AS rt_conversions',
                'rt.cost AS rt_cost',
                'rt.profit AS rt_profit',
                DB::raw('(rt.cost + rt.profit) AS rt_revenue')
            )
            ->groupBy(
                'u.id',
                'u.name',
                't.code',
                'rt.id',
                'rt.date',
                'rt.clicks',
                'rt.conversions',
                'rt.cost',
                'rt.profit'
            );


        // -----------------------------------------
        // 4️⃣ CONSOLIDAÇÃO POR COPYWRITER
        //
        // Agora SOMA tudo corretamente
        // -----------------------------------------
        $baseQuery = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'agent_name',
                DB::raw('COUNT(DISTINCT creative_code) AS total_creatives'),

                DB::raw('SUM(rt_clicks) AS total_clicks'),
                DB::raw('SUM(rt_conversions) AS total_conversions'),
                DB::raw('SUM(rt_cost) AS total_cost'),
                DB::raw('SUM(rt_profit) AS total_profit'),
                DB::raw('SUM(rt_revenue) AS total_revenue'),

                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS total_roi')
            )
            ->groupBy('user_id', 'agent_name');


        // -----------------------------------------
        // 5️⃣ Filtro de copywriters (multiselect)
        // -----------------------------------------
        if ($request->filled('copywriters')) {
            $baseQuery->whereIn('agent_name', (array) $request->copywriters);
        }

        $copies = $baseQuery->orderByDesc('total_profit')->paginate(20);


        // -----------------------------------------
        // 6️⃣ CRIATIVOS POR AGENTE (expansão)
        //
        // Aqui cada entrada é um registro REAL do RedTrack
        // -----------------------------------------
        $creativesByAgent = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'creative_code',
                DB::raw('SUM(rt_clicks) AS clicks'),
                DB::raw('SUM(rt_conversions) AS conversions'),
                DB::raw('SUM(rt_cost) AS cost'),
                DB::raw('SUM(rt_profit) AS profit'),
                DB::raw('SUM(rt_revenue) AS revenue'),
                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS roi')
            )
            ->groupBy('user_id', 'creative_code')
            ->orderBy('profit', 'desc')
            ->get()
            ->groupBy('user_id');


        // -----------------------------------------
        // 7️⃣ Retorna tudo para a view
        // -----------------------------------------
        return view('admin.copy', compact(
            'copies',
            'allCopywriters',
            'startDate',
            'endDate',
            'creativesByAgent'
        ));
    }




    public function allCopywritersArray()
    {
        return DB::table('users AS u')
            ->join('user_roles AS ur', 'ur.user_id', '=', 'u.id')
            ->where('ur.role_id', 2) // COPYWRITER
            ->orderBy('u.name')
            ->pluck('u.name')
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
        $users = User::select('id', 'name', 'email')->get();

        return view('admin.time', compact('teamPerformance', 'roles', 'users'));
    }

    public function dashboard(Request $request)
    {
        $lastTask = RedtrackReport::orderBy('updated_at', 'desc')->first()->updated_at;
        $lastUpdate = Carbon::create($lastTask)->format('d/m/Y H:i:s');
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // filtros opcionais
        $aliasFilter = $request->input('alias', []);

        // =====================================================================
        // 🔹 BUSCA DADOS GERAIS (totais no período)
        // =====================================================================

        $reports = RedtrackReport::whereBetween('date', [$startDate, $endDate]);

        if (!empty($aliasFilter)) {
            $reports = $reports->whereIn('alias', $aliasFilter);
        }

        $totalCost = (clone $reports)->sum('cost');
        $totalProfit = (clone $reports)->sum('profit');

        $roi = $totalCost > 0 ? $totalProfit / $totalCost : 0;

        $totals = [
            'cost'   => $totalCost,
            'profit' => $totalProfit,
            'roi'    => $roi,
        ];

        // =====================================================================
        // 🔹 LISTA DE MESES FIXA (Jan–Dez)
        // =====================================================================
        $monthsList = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];

        // =====================================================================
        // 🔹 ALIASES FIXOS DO GRÁFICO
        // =====================================================================

        $aliases = ['facebook', 'tiktok', 'taboola', 'native'];

        // =====================================================================
        // 🔹 BUSCA LUCRO POR MÊS E POR ALIAS
        // =====================================================================

        $monthlyProfit = RedtrackReport::selectRaw("
            MONTH(date) as month_number,
            DATE_FORMAT(date, '%b') as month_name,
            LOWER(alias) as alias,
            SUM(profit) as profit
        ")
            ->groupBy('month_number', 'month_name', 'alias')
            ->orderBy('month_number');

        if ($aliasFilter) {
            $monthlyProfit->whereIn('alias', $aliasFilter);
        }
        $monthlyProfit = $monthlyProfit->get();
        // =====================================================================
        // 🔹 INICIA CHARTDATA: todos meses com valores zerados
        // =====================================================================

        $chartData = [];
        foreach ($monthsList as $m) {
            $chartData[$m] = [
                'facebook' => 0,
                'tiktok'   => 0,
                'taboola'  => 0,
                'native'   => 0,
            ];
        }
        // =====================================================================
        // 🔹 PREENCHER OS DADOS (agrupando NATIVE)
        // =====================================================================

        foreach ($monthlyProfit as $row) {
            $monthName = $row->month_name;        // Jan / Fev / Mar
            $alias     = strtolower($row->alias); // facebook / tiktok / ...
            $profit    = (float) $row->profit;
            if (in_array($alias, ['facebook', 'tiktok', 'taboola'])) {
                // plataforma conhecida
                $chartData[$monthName][$alias] += $profit;
            } else {
                // qualquer outra entra em NATIVE
                $chartData[$monthName]['native'] += $profit;
            }
        }
        // =====================================================================
        // 🔹 CALCULA MAIOR VALOR PARA ESCALA DO GRAFICO
        // =====================================================================
        $maxValue = 0;
        foreach ($chartData as $month => $platforms) {
            foreach ($platforms as $value) {
                if ($value > $maxValue) {
                    $maxValue = $value;
                }
            }
        }
        if ($maxValue <= 0) {
            $maxValue = 1; // evita divisão por zero
        }

        // =====================================================================
        // 🔹 BUSCA OUTRAS MÉTRICAS (SUAS TABELAS DE FONTE)
        // =====================================================================

        $sources = RedtrackReport::selectRaw("
            alias,
            SUM(cost) as total_cost,
            SUM(profit) as total_profit,
            SUM(clicks) as total_clicks,
            SUM(conversions) as total_conversions,
            (SUM(profit)/NULLIF(SUM(cost),0)) as roi
        ")
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('alias')
            ->orderBy('alias')
            ->get();

        // agrupamento das contas por alias
        $accountsByAlias = RedtrackReport::selectRaw("
            alias,
            source,
            SUM(cost) as total_cost,
            SUM(profit) as total_profit,
            SUM(conversions) as total_conversions,
            SUM(clicks) as total_clicks,
            (SUM(profit)/NULLIF(SUM(cost),0)) as roi
        ")
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('alias', 'source')
            ->orderBy('alias')
            ->orderBy('roi', 'desc')
            ->get()
            ->groupBy('alias');

        // =====================================================================
        // 🔹 RETORNO FINAL PARA A VIEW
        // =====================================================================

        return view("admin.dashboard", compact(
            'totals',
            'startDate',
            'endDate',
            'chartData',
            'aliases',
            'maxValue',
            'sources',
            'accountsByAlias',
            'lastUpdate',
        ));
    }



    public function creativeHistory(Request $request)
    {
        $creative = $request->input('creative');
        if (!$creative) {
            return response()->json(['error' => 'Creative code missing'], 400);
        }

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $history = DB::table('redtrack_reports')
            ->where('name', $creative)
            ->whereBetween('date', [$startDate, $endDate])
            ->select(
                'date',
                DB::raw('SUM(clicks) AS clicks'),
                DB::raw('SUM(cost) AS cost'),
                DB::raw('SUM(profit) AS profit'),
                DB::raw('ROUND(SUM(profit) / NULLIF(SUM(cost),0), 4) AS roi')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return response()->json($history);
    }
}
