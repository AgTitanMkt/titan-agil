<?php

namespace App\Http\Controllers;

use App\Models\RedtrackReport;
use App\Models\User;
use App\Services\Dashboard\AgentsService;
use App\Services\Dashboard\CopaProfitService;
use App\Services\Dashboard\SquadService;
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
        // 1️⃣ Datas
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        // 2️⃣ filtro (nomes) vindo do request
        $editorFilter = $request->input('editors'); // pode ser array ou null

        // 3️⃣ lista de editores
        $allEditors = $this->allEditorsArray();

        // 4️⃣ busca dos usuários com role=editor
        $editors = User::withRole(3)->get();

        // 5️⃣ aplica filtros corretamente
        foreach ($editors as $editor) {
            $editor->applyFilter($startDate, $endDate, $editorFilter);
            $editor->metrics = $editor->tasks;
        }

        // 6️⃣ remove quem não tem métricas
        $editors = $editors->filter(fn($e) => count($e->metrics));

        // 7️⃣ ordena pelo lucro
        $editors = $editors->sortByDesc(fn($e) => $e->metrics->sum('total_profit'))->values();

        // 8️⃣ retorna view
        return view('admin.editors', compact(
            'editors',
            'allEditors',
            'startDate',
            'endDate',
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

        $copywriters = $request->input('copywriters');

        // -----------------------------------------
        // 2️⃣ Lista completa de copywriters (multiselect)
        // -----------------------------------------
        $allCopywriters = $this->allCopywritersArray();

        $copies = User::withRole(2)
            ->get();

        foreach ($copies as $copy) {
            $copy->applyFilter($startDate, $endDate, $copywriters);
            $copy->metrics = $copy->tasks;
        }

        $copies = $copies->filter(function ($copy) {
            return count($copy->metrics);
        });
        $copies = $copies->sortByDesc(function ($copy) {
            return $copy->metrics->sum('total_profit');
        })->values();

        // dd($copies->first()->metrics);

        return view('admin.copy', compact(
            'copies',
            'allCopywriters',
            'startDate',
            'endDate',
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
    public function allEditorsArray()
    {
        return DB::table('users AS u')
            ->join('user_roles AS ur', 'ur.user_id', '=', 'u.id')
            ->where('ur.role_id', 3) // Editor
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

        $aliases = ['facebook', 'tiktok', 'google', 'native'];

        // =====================================================================
        // 🔹 BUSCA LUCRO POR MÊS E POR ALIAS
        // =====================================================================

        $monthlyProfit = RedtrackReport::selectRaw("
            MONTH(date) as month_number,
            DATE_FORMAT(date, '%b') as month_name,
            LOWER(alias) as alias,
            SUM(profit) as profit,
            SUM(cost) as cost
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
                'facebook' => [
                    'profit' => 0,
                    'cost'   => 0,
                    'roi'    => 0,
                ],
                'tiktok'   => [
                    'profit' => 0,
                    'cost'   => 0,
                    'roi'    => 0,
                ],
                'google'  => [
                    'profit' => 0,
                    'cost'   => 0,
                    'roi'    => 0,
                ],
                'native'   => [
                    'profit' => 0,
                    'cost'   => 0,
                    'roi'    => 0,
                ],
            ];
        }
        // =====================================================================
        // 🔹 PREENCHER OS DADOS (agrupando NATIVE)
        // =====================================================================

        foreach ($monthlyProfit as $row) {
            $monthName = $row->month_name;        // Jan / Fev / Mar
            $alias     = strtolower($row->alias); // facebook / tiktok / ...
            $profit    = (float) $row->profit;
            $cost    = (float) $row->cost;
            if (in_array($alias, ['facebook', 'tiktok', 'google'])) {
                // plataforma conhecida
                $chartData[$monthName][$alias]['profit'] += $profit;
                $chartData[$monthName][$alias]['cost'] += $cost;
            } else {
                // qualquer outra entra em NATIVE
                $chartData[$monthName]['native']['profit'] += $profit;
                $chartData[$monthName]['native']['cost'] += $cost;
            }
        }
        $chartWithRoi = $chartData;

        foreach ($chartWithRoi as $month => $platforms) {
            foreach ($platforms as $aliasName => $values) {

                $profit = $values['profit'] ?? 0;
                $cost   = $values['cost']   ?? 0;

                $roi = ($cost > 0)
                    ? round($profit / $cost, 4)
                    : 0;

                // Atualiza no array FINAL
                $chartWithRoi[$month][$aliasName]['roi'] = $roi;
            }
        }

        $chartData = $chartWithRoi;

        // =====================================================================
        // 🔹 CALCULA MAIOR VALOR PARA ESCALA DO GRAFICO
        // =====================================================================
        $maxValue = 0;
        foreach ($chartData as $month => $platforms) {
            foreach ($platforms as $value) {
                if ($value['profit'] > $maxValue) {
                    $maxValue = $value['profit'];
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
            ->orderBy('total_profit', 'desc')
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
            ->orderBy('total_profit', 'desc')
            ->get()
            ->groupBy('alias');

        $copaService = new CopaProfitService();
        $copaData = $copaService->make();
        $podium = $copaData['podium'];
        $copiesPodium = $copaData['copiesPodium'];
        $editorsPodium = $copaData['editorsPodium'];
        $copaYear = $copaData['copaYear'];
        $copaMonths = $copaData['copaMonths'];
        $copaPrize = $copaData['copaPrize'];
        $editorPrize = $copaData['editorPrize'];
        $copiePrize = $copaData['copiePrize'];
        $aliasRanking = new SquadService()->rankByAlias(4);
        $aliasRanking = $aliasRanking->filter(function ($item) {
            return $item['profit'] > 0;
        });

        $expectedMonthlyProfit = 1000000;

        //calculando status da meta
        $month = substr(Carbon::now()->locale('en')->monthName, 0, 3);
        $total = 0;
        $profitMonth = collect($chartData[$month])->map(function ($data) use (&$total) {
            $total += $data['profit'];
            return $data['profit'];
        });
        $target = number_format($total / $expectedMonthlyProfit, 2) * 100;

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
            'expectedMonthlyProfit',
            'podium',
            'copiesPodium',
            'editorsPodium',
            'copaYear',
            'copaMonths',
            'copaPrize',
            'editorPrize',
            'copiePrize',
            'aliasRanking',
            'target',
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

    public function gestores(){
        return view("admin.gestores");
    }

    
}