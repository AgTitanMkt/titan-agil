<?php

namespace App\Http\Controllers;

use App\Models\Nicho;
use App\Models\RedtrackReport;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\ValidatedCreative;
use App\Services\Dashboard\AgentsService;
use App\Services\Dashboard\CopaProfitService;
use App\Services\Dashboard\SquadService;
use App\Services\Tasks\TasksService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

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

        $editors = $request->input('editors');

        $nicho = $request->input('nicho');

        $selectedEditorId = $request->input('editor_id');

        // -----------------------------------------
        // 2️⃣ Lista completa de copywriters (multiselect)
        // -----------------------------------------
        $allEditors = $this->allEditorsArray();

        $metricsEditors = CopaProfitService::AgentsMetrics($startDate, $endDate, $editors);


        $editors = User::withRole(3)
            ->get();
        foreach ($editors as $editor) {
            $editor->metrics = $metricsEditors[$editor->id] ?? collect();
        }

        // $editors = $editors->filter(fn($editor) => $editor->metrics->isNotEmpty());

        $editors = $editors->sortByDesc(function ($editor) {
            return $editor->metrics->sum('total_profit');
        })->values();

        $editorsPerformance = $editors;

        if ($nicho) {
            $editorsPerformance = $editorsPerformance
                ->map(function ($editor) use ($nicho) {

                    // 🔹 mantém só métricas do nicho
                    $editor->metrics = $editor->metrics
                        ->where('nicho_name', $nicho)
                        ->values();

                    return $editor;
                })
                ->filter(function ($editor) {

                    // 🔹 remove editor sem métricas
                    return $editor->metrics->isNotEmpty();
                })
                ->values();
        }

        //dados para dashboard

        $totalProduzido = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])->count();

        $testadas = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])
            ->whereHas('redtrackReports', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ]);
            })
            ->get();
        $totalTestado = $testadas->count();
        $emPotencial =  ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Potential', 1)
            ->count();
        $validados =  ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Validated', 1)
            ->count();

        $nichosService = new TasksService();
        $dataNichos = $nichosService->dataNichos();
        $topProfitNicho = $dataNichos->sortByDesc(function ($item) {
            return (float) $item->total_profit;
        })->take(3);
        $topRoiNicho = $dataNichos->sortByDesc(function ($item) {
            return (float) $item->roi;
        })->take(3);
        $totalProfitNichos = $dataNichos->sum('total_profit');
        $nichosBar = $dataNichos->map(function ($nicho) use ($totalProfitNichos) {
            $profit = (float) $nicho->total_profit;

            $nicho->percent = $totalProfitNichos > 0
                ? round(($profit / $totalProfitNichos) * 100, 2)
                : 0;

            return $nicho;
        });

        $totalProfitEditors = $editors->sum(function ($editor) {
            return $editor->metrics->sum('total_profit');
        });
        $topEditorsRoi = $editors
            ->filter(function ($editor) {
                return $editor->metrics->sum('total_cost') > 0;
            })
            ->sortByDesc(function ($editor) {
                return
                    $editor->metrics->sum('total_profit') /
                    $editor->metrics->sum('total_cost');
            })
            ->values()
            ->take(3);

        $topEditorsProfit = $editors->sortByDesc(function ($editor) {
            return $editor->metrics->sum('total_profit');
        })->values()->take(3);

        $agentesServices = new AgentsService($startDate, $endDate);
        $duplasData = $agentesServices->duoMetrics();
        $topDuplaRoi = $duplasData->sortByDesc(function ($dupla) {
            return $dupla->roi;
        })->values()->take(3);

        $topDuplaProfit = $duplasData->sortByDesc(function ($dupla) {
            return $dupla->total_profit;
        })->values()->take(3);


        //dados garfico individuais
        $chartIndividualData = $editors->map(function ($editor) {

            $byNiche = $editor->metrics->groupBy('nicho_name')->map(function ($metrics) {
                return [
                    'total_profit' => $metrics->sum('total_profit'),
                    'total_cost'   => $metrics->sum('total_cost'),
                    'produced'     => $metrics->count(),
                ];
            });

            return [
                'editor_id' => $editor->id,
                'name' => $editor->name,
                'label' => collect(explode(' ', $editor->name))
                    ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode(''),
                'by_niche' => $byNiche, // 👈 DADO COMPLETO
            ];
        })->values();


        if ($selectedEditorId) {
            $sinergyCopy = $duplasData
                ->where('copy_id', $selectedEditorId)
                ->first();
        } else {
            $sinergyCopy = $duplasData
                ->groupBy('editor_id')
                ->map(fn($items) => [
                    'editor_id' => $items->first()->editor_id,
                    'editor_name' => $items->first()->editor_name,
                    'total_profit' => $items->sum('total_profit'),
                ])
                ->sortByDesc('total_profit')
                ->first();
        }
        $sinergyCopy = is_array($sinergyCopy) ? $sinergyCopy['editor_id'] : $sinergyCopy->editor_id;
        $synergyData = $duplasData
            ->where('editor_id', $sinergyCopy)
            ->values();
        $chartSynergyData = $synergyData->map(fn($d) => [
            'x' => (float) $d->roi,
            'y' => (int) $d->total_creatives,
            'r' => max(6, sqrt(abs($d->total_profit)) / 20),
            'label' => $d->dupla,
            'editor' => $d->editor_name,
            'copywriter' => $d->copy_name,
            'profit' => (float) $d->total_profit,
            'roi' => (float) $d->roi,
            'produced' => (int) $d->total_creatives,
        ])->values();


        return view('admin.editors', compact(
            'editors',
            'allEditors',
            'startDate',
            'endDate',
            'totalProduzido',
            'totalTestado',
            'emPotencial',
            'validados',
            'topProfitNicho',
            'topRoiNicho',
            'nichosBar',
            'totalProfitNichos',
            'totalProfitEditors',
            'topEditorsProfit',
            'topEditorsRoi',
            'topDuplaRoi',
            'topDuplaProfit',
            'chartIndividualData',
            'chartSynergyData',
            'selectedEditorId',

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

        $selectedCopyId = $request->input('copy_id');

        // -----------------------------------------
        // 2️⃣ Lista completa de copywriters (multiselect)
        // -----------------------------------------
        $allCopywriters = $this->allCopywritersArray();

        $metricsCopies = CopaProfitService::AgentsMetrics($startDate, $endDate, $copywriters);


        $copies = User::withRole(2)
            ->get();

        foreach ($copies as $copy) {
            $copy->metrics = $metricsCopies[$copy->id] ?? collect();
        }

        // $copies = $copies->filter(fn($copy) => $copy->metrics->isNotEmpty());

        $copies = $copies->sortByDesc(function ($copy) {
            return $copy->metrics->sum('total_profit');
        })->values();

        //dados para dashboard

        $totalProduzido = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])->count();

        $testadas = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])
            ->whereHas('redtrackReports', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [
                    $startDate->startOfDay(),
                    $endDate->endOfDay()
                ]);
            })
            ->get();
        $totalTestado = $testadas->count();
        $emPotencial =  ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Potential', 1)
            ->count();
        $validados =  ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Validated', 1)
            ->count();

        $nichosService = new TasksService();
        $dataNichos = $nichosService->dataNichos();
        $topProfitNicho = $dataNichos->sortByDesc(function ($item) {
            return (float) $item->total_profit;
        })->take(3);
        $topRoiNicho = $dataNichos->sortByDesc(function ($item) {
            return (float) $item->roi;
        })->take(3);
        $totalProfitNichos = $dataNichos->sum('total_profit');
        $nichosBar = $dataNichos->map(function ($nicho) use ($totalProfitNichos) {
            $profit = (float) $nicho->total_profit;

            $nicho->percent = $totalProfitNichos > 0
                ? round(($profit / $totalProfitNichos) * 100, 2)
                : 0;

            return $nicho;
        });

        $totalProfitCopies = $copies->sum(function ($copy) {
            return $copy->metrics->sum('total_profit');
        });

        $topCopiesRoi = $copies
            ->filter(function ($copy) {
                return $copy->metrics->sum('total_cost') > 0;
            })
            ->sortByDesc(function ($copy) {
                return
                    $copy->metrics->sum('total_profit') /
                    $copy->metrics->sum('total_cost');
            })
            ->values()
            ->take(3);

        $topCopiesProfit = $copies->sortByDesc(function ($copy) {
            return $copy->metrics->sum('total_profit');
        })->values()->take(3);

        $agentesServices = new AgentsService($startDate, $endDate);
        $duplasData = $agentesServices->duoMetrics();
        $topDuplaRoi = $duplasData->sortByDesc(function ($dupla) {
            return $dupla->roi;
        })->values()->take(3);

        $topDuplaProfit = $duplasData->sortByDesc(function ($dupla) {
            return $dupla->total_profit;
        })->values()->take(3);

        $chartIndividualData = $copies
            ->filter(fn($copy) => $copy->metrics->count() > 0) // ignora quem não tem dados
            ->map(function ($copy) {

                $totalProfit = $copy->metrics->sum('total_profit');
                $totalCost   = $copy->metrics->sum('total_cost');

                return [
                    'x'     => $totalCost > 0 ? round($totalProfit / $totalCost, 2) : 0, // ROI
                    'y'     => $copy->metrics->count(),                                  // Produzidos
                    'r'     => max(8, sqrt(abs($totalProfit)) / 15),                    // tamanho da bolha
                    'label' => collect(explode(' ', $copy->name))
                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                        ->take(2)
                        ->implode(''),
                    'name'  => $copy->name,
                    'profit' => round($totalProfit, 2),
                ];
            })
            ->values();

        if ($selectedCopyId) {
            $sinergyCopy = $duplasData
                ->where('copy_id', $selectedCopyId)
                ->first();
        } else {
            $sinergyCopy = $duplasData
                ->groupBy('copy_id')
                ->map(fn($items) => [
                    'copy_id' => $items->first()->copy_id,
                    'copy_name' => $items->first()->copy_name,
                    'total_profit' => $items->sum('total_profit'),
                ])
                ->sortByDesc('total_profit')
                ->first();
        }
        $sinergyCopy = is_array($sinergyCopy) ? $sinergyCopy['copy_id'] : $sinergyCopy->copy_id;
        $synergyData = $duplasData
            ->where('copy_id', $sinergyCopy)
            ->values();
        $chartSynergyData = $synergyData->map(fn($d) => [
            'x' => (float) $d->roi,
            'y' => (int) $d->total_creatives,
            'r' => max(6, sqrt(abs($d->total_profit)) / 20),
            'label' => $d->dupla,
            'editor' => $d->editor_name,
            'profit' => (float) $d->total_profit,
            'roi' => (float) $d->roi,
            'produced' => (int) $d->total_creatives,
        ])->values();


        return view('admin.copy', compact(
            'copies',
            'allCopywriters',
            'startDate',
            'endDate',
            'totalProduzido',
            'totalTestado',
            'emPotencial',
            'validados',
            'topProfitNicho',
            'topRoiNicho',
            'nichosBar',
            'totalProfitNichos',
            'totalProfitCopies',
            'topCopiesProfit',
            'topCopiesRoi',
            'topDuplaRoi',
            'topDuplaProfit',
            'chartIndividualData',
            'chartSynergyData',
            'selectedCopyId',

        ));
    }

    public function agents(
        Request $request,
        string $type = 'editors',
        string $collaborator = 'IN'
    ) {
        // 🔹 Validar o parâmetro - vem via URL, não via query string
        $collaborator = in_array($collaborator, ['IN', 'EX'])
            ? $collaborator
            : 'IN';

        $isCopy = $type === 'copywriters';


        $roleId        = $isCopy ? 2 : 3;
        $idParam       = $isCopy ? 'copy_id' : 'editor_id';
        $agentsVar     = $isCopy ? 'copywriters' : 'editors';

        // -------------------------------------------------
        // 1️⃣ Datas
        // -------------------------------------------------
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        if ($request->input('date_from')) {
            if (!$request->input('date_to')) {
                $endDate =  Carbon::parse($request->input('date_from'))->endOfDay();
            } else {
                $endDate =  Carbon::parse($request->input('date_to'))->endOfDay();
            }
        } else {
            $endDate =  Carbon::now()->endOfMonth();
        }



        $agentsFilter     = $request->input($agentsVar);
        $nicho            = $request->input('nicho');
        $source            = $request->input('source');
        $selectedAgentId  = $request->input($idParam);

        $source = $source
            ? strtolower(trim($source))
            : null;

        // -------------------------------------------------
        // 2️⃣ Lista completa - FILTRO MULTISELECT
        // -------------------------------------------------
        // $allAgents = $isCopy
        //     ? $this->allCopywritersArray()
        //     : $this->allEditorsArray();

        $allAgents = User::withRole($roleId)
            ->where('tipo_colaborador', $collaborator)
            ->where('active', 1)
            ->whereNotNull('name')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();
        // puxando apenas os actives do banco

        // filtro multiselct recebe apenas intenros na rota /copywriters/IN, externos na rota /copywriters/EX e normal para editores 
        // /admin/agents/copywriters/IN
        // /admin/agents/copywriters/EX

        $allNiches = Nicho::groupBy('name')->pluck('name');

        $metricsAgents = CopaProfitService::AgentsMetrics(
            $startDate,
            $endDate,
            $agentsFilter,
            $source == 'TOTAL' ? null : $source
        );



        // $agents = User::withRole($roleId)->get();
        $agents = User::withRole($roleId)
            ->where('tipo_colaborador', $collaborator)
            ->where('active', 1)
            ->get();
        // puxando apenas os actives do banco

        // REMOVIDO PORQUE ESTAVA DUPLICANDO OS IDS DE CRIATIVO. 
        // foreach ($agents as $agent) {
        //     $agent->applyFilter(
        //         $startDate,
        //         $endDate,
        //     );
        //     $agent->metrics = $metricsAgents[$agent->id] ?? collect();
        // }

        // NOVA LOGICA QUE PERMITE; reverse() inverte a ordem da collection, mantem o mais recente. unique('code') remove duplicados usando creative_code. values() reindexa a collection
        foreach ($agents as $agent) {
            $agent->applyFilter(
                $startDate,
                $endDate,
            );

            $metrics = $metricsAgents[$agent->id] ?? collect();

            // REMOVE DUPLICADOS USANDO creative_code
            $metrics = $metrics
                ->reverse()
                ->unique('code')
                ->values()
                ->reverse();

            $agent->metrics = $metrics;
        }

        // contando produzidos no periodo
        $agents = $agents->map(function ($agent) {
            $agent->produzidos = $agent->metrics->sum('produzido');
            return $agent;
        });

        // removendo agentes sem criativos
        $agents = $agents->filter(function ($agent) {
            return $agent->metrics->isNotEmpty();
        });

        $agents = $agents->sortByDesc(
            fn($a) => $a->metrics->sum('total_profit')
        )->values();

        // dd($agents->first()->metrics);

        // -------------------------------------------------
        // 3️⃣ Filtro por nicho (IGUAL)
        // -------------------------------------------------
        if ($nicho && $nicho !== "TOTAL") {
            $agents = $agents
                ->map(function ($agent) use ($nicho) {
                    $agent->metrics = $agent->metrics
                        ->where('nicho_name', $nicho)
                        ->values();
                    return $agent;
                })
                ->filter(fn($agent) => $agent->metrics->isNotEmpty())
                ->values();
        }


        // -------------------------------------------------
        // 4️⃣ Dashboard (IGUAL)
        // -------------------------------------------------
        $totalProduzido = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])->count();


        $testadas = Task::whereBetween('created_at', [
            $startDate->startOfDay(),
            $endDate->endOfDay()
        ])->whereHas('redtrackReports', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [
                $startDate->startOfDay(),
                $endDate->endOfDay()
            ]);
        })->get();

        $totalTestado = $testadas->count();
        $emPotencial  = ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Potential', 1)->count();

        $validados = ValidatedCreative::whereIn('ad', $testadas->pluck('code'))
            ->where('is_Validated', 1)->count();

        // -------------------------------------------------
        // 5️⃣ Nichos (IGUAL)
        // -------------------------------------------------
        $dataNichos = (new TasksService())->dataNichos();

        $topProfitNicho = $dataNichos->sortByDesc('total_profit')->take(3);
        $topRoiNicho    = $dataNichos->sortByDesc('roi')->take(3);

        $totalProfitNichos = $dataNichos->sum('total_profit');

        $minPercent = 7;

        $nichosBar = $dataNichos->map(function ($nicho) use ($totalProfitNichos, $minPercent) {

            if ($totalProfitNichos <= 0 || $nicho->total_profit <= 0) {
                $nicho->percent = 0;
                return $nicho;
            }

            $percent = round(($nicho->total_profit / $totalProfitNichos) * 100, 2);

            // 🔒 piso visual
            $nicho->percent = max($percent, $minPercent);

            return $nicho;
        });


        // -------------------------------------------------
        // 6️⃣ Top agentes (IGUAL)
        // -------------------------------------------------
        $totalProfitAgents = $agents->sum(
            fn($a) => $a->metrics->sum('total_profit')
        );

        $topAgentsRoi = $agents
            ->filter(fn($a) => $a->metrics->sum('total_cost') > 0)
            ->sortByDesc(
                fn($a) =>
                $a->metrics->sum('total_profit') /
                    $a->metrics->sum('total_cost')
            )
            ->take(3)
            ->values();

        $topAgentsProfit = $agents
            ->sortByDesc(fn($a) => $a->metrics->sum('total_profit'))
            ->take(3)
            ->values();

        // -------------------------------------------------
        // 7️⃣ SINERGIA (IGUAL À ORIGINAL)
        // -------------------------------------------------
        $duplasData = (new AgentsService($startDate, $endDate))->duoMetrics();

        $topDuplaRoi = $duplasData
            ->sortByDesc('roi')
            ->take(3)
            ->values();

        $topDuplaProfit = $duplasData
            ->sortByDesc('total_profit')
            ->take(3)
            ->values();

        if ($selectedAgentId) {
            $sinergyPivot = $duplasData
                ->where($isCopy ? 'copy_id' : 'copy_id', $selectedAgentId)
                ->first();
        } else {
            $sinergyPivot = $duplasData
                ->groupBy('editor_id')
                ->map(fn($items) => [
                    'editor_id' => $items->first()->editor_id,
                    'total_profit' => $items->sum('total_profit'),
                ])
                ->sortByDesc('total_profit')
                ->first();
        }

        $pivotEditorId = is_array($sinergyPivot)
            ? $sinergyPivot['editor_id']
            : $sinergyPivot->editor_id;

        $chartSynergyData = $duplasData
            ->where('editor_id', $pivotEditorId)
            ->map(fn($d) => [
                'x' => (float)$d->roi,
                'y' => (int)$d->total_creatives,
                'label' => $d->dupla,
                'editor' => $d->editor_name,
                'copywriter' => $d->copy_name,
                'profit' => (float)$d->total_profit,
                'roi' => (float)$d->roi,
                'produced' => (int)$d->total_creatives,
                'testados' => (int)$d->tested
            ])
            ->values();


        // -------------------------------------------------
        // 8️⃣ Gráfico individual (IGUAL)
        // -------------------------------------------------
        $chartIndividualData = $agents->map(function ($agent) {
            return [
                'editor_id' => $agent->id,
                'name' => $agent->name,
                'label' => collect(explode(' ', $agent->name))
                    ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                    ->take(2)
                    ->implode(''),

                'by_niche' => $agent->metrics
                    ->groupBy('nicho_name')
                    ->map(function ($m) {
                        return [
                            'total_profit' => $m->sum('total_profit'),
                            'total_cost'   => $m->sum('total_cost'),

                            // ✅ PRODUÇÃO REAL
                            'produced'     => $m->sum('produzido'),

                            // ✅ TESTES REAIS
                            'tested'       => $m->sum('testados'),
                        ];
                    }),
            ];
        });

        $chartIndividualData = $chartIndividualData->map(function ($agent) {
            $agent['produceds'] = $agent['by_niche']->sum('produced');
            return $agent;
        })->filter(function ($agent) {
            return $agent['produceds'] > 0;
        })->values();


        return view('admin.agents', compact(
            'agents',
            'allAgents',
            'startDate',
            'endDate',
            'totalProduzido',
            'totalTestado',
            'emPotencial',
            'validados',
            'topProfitNicho',
            'topRoiNicho',
            'nichosBar',
            'totalProfitNichos',
            'totalProfitAgents',
            'topAgentsProfit',
            'topAgentsRoi',
            'topDuplaRoi',
            'topDuplaProfit',
            'chartIndividualData',
            'chartSynergyData',
            'selectedAgentId',
            'type',
            'allNiches',
            'collaborator'
            // nova para copy INTERNO E EXTERNO
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
        // 🚀 OTIMIZAÇÃO: Query simplificada e sem dupla agregação
        $query = DB::table('vw_creatives_performance')
            ->select(
                'role_id',
                'role_name',
                'user_id',
                'agent_name',
                DB::raw('COUNT(DISTINCT creative_code) AS total_creatives'),
                DB::raw('SUM(clicks) AS total_clicks'),
                DB::raw('SUM(conversions) AS total_conversions'),
                DB::raw('SUM(cost) AS total_cost'),
                DB::raw('SUM(profit) AS total_profit'),
                DB::raw('SUM(revenue) AS total_revenue'),
                DB::raw('ROUND((SUM(profit) / NULLIF(SUM(cost), 0)), 4) AS total_roi'),
                DB::raw('ROUND(AVG(roas), 2) AS avg_roas'),
                DB::raw('ROUND(AVG(ctr), 4) AS avg_ctr'),
                DB::raw('ROUND(AVG(cpm), 2) AS avg_cpm')
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
        $users = User::select('id', 'name', 'email')
            ->whereIn('id', function ($q) {
                $q->select('user_id')->from('user_roles');
            })
            ->get();

        return view('admin.time', compact('teamPerformance', 'roles', 'users'));
    }

    public function dashboard(Request $request)
    {
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfYear(); // <- de janeiro ate hoje

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        $aliasFilter = $request->input('alias', []);

        $cacheKey = 'dashboard_' . md5($startDate . $endDate . implode(',', $aliasFilter));

        $cached = cache()->remember($cacheKey, 300, function () use ($startDate, $endDate, $aliasFilter) {


            $lastTask   = RedtrackReport::orderBy('updated_at', 'desc')->first();
            $lastUpdate = $lastTask
                ? Carbon::parse($lastTask->updated_at)->format('d/m/Y H:i:s')
                : now()->format('d/m/Y H:i:s');

            // query base
            $baseQuery = RedtrackReport::whereBetween('date', [$startDate, $endDate]);
            if (!empty($aliasFilter)) {
                $baseQuery->whereIn('alias', $aliasFilter);
            }

            // totais gerais 
            $totalRevenue = (clone $baseQuery)->sum('revenue');
            $totalCost    = (clone $baseQuery)->sum('cost');
            $totalProfit  = (clone $baseQuery)->sum('profit');
            $roi          = $totalCost > 0 ? $totalProfit / $totalCost : 0;

            $totals = [
                'revenue' => $totalRevenue,
                'cost'    => $totalCost,
                'profit'  => $totalProfit,
                'roi'     => $roi,
            ];

            // grafico mensal
            $monthsList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $aliases    = ['facebook', 'tiktok', 'google', 'native'];

            $monthlyProfitQuery = RedtrackReport::selectRaw("
            YEAR(date)              AS year,
            MONTH(date)             AS month_number,
            DATE_FORMAT(date, '%b') AS month_name,
            LOWER(alias)            AS alias,
            SUM(revenue)            AS revenue,
            SUM(profit)             AS profit,
            SUM(cost)               AS cost
        ")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('year', 'month_number', 'month_name', 'alias')
                ->orderBy('year')
                ->orderBy('month_number');

            if (!empty($aliasFilter)) {
                $monthlyProfitQuery->whereIn('alias', $aliasFilter);
            }

            $monthlyProfit = $monthlyProfitQuery->get();

            // inicializa estrutura zerada
            $chartData = [];
            foreach ($monthsList as $m) {
                $chartData[$m] = [
                    'facebook' => ['revenue' => 0, 'profit' => 0, 'cost' => 0, 'roi' => 0],
                    'tiktok'   => ['revenue' => 0, 'profit' => 0, 'cost' => 0, 'roi' => 0],
                    'google'   => ['revenue' => 0, 'profit' => 0, 'cost' => 0, 'roi' => 0],
                    'native'   => ['revenue' => 0, 'profit' => 0, 'cost' => 0, 'roi' => 0],
                ];
            }

            foreach ($monthlyProfit as $row) {
                $monthName = $row->month_name;
                $alias     = strtolower($row->alias ?? '');
                $revenue   = (float) $row->revenue;
                $profit    = (float) $row->profit;
                $cost      = (float) $row->cost;

                // facebook, tiktok, google ficam no próprio alias; todo o resto vai em 'native'
                $bucket = in_array($alias, ['facebook', 'tiktok', 'google']) ? $alias : 'native';

                $chartData[$monthName][$bucket]['revenue'] += $revenue;
                $chartData[$monthName][$bucket]['profit']  += $profit;
                $chartData[$monthName][$bucket]['cost']    += $cost;
            }

            // calcula ROI por bucket/mes
            foreach ($chartData as $month => $platforms) {
                foreach ($platforms as $aliasName => $values) {
                    $c = $values['cost'] ?? 0;
                    $p = $values['profit'] ?? 0;
                    $chartData[$month][$aliasName]['roi'] = $c > 0 ? round($p / $c, 4) : 0;
                }
            }

            $maxValue = 1;
            foreach ($chartData as $platforms) {
                foreach ($platforms as $values) {
                    if ($values['profit'] > $maxValue) {
                        $maxValue = $values['profit'];
                    }
                }
            }

            // fontes
            $sources = RedtrackReport::selectRaw("
            alias,
            SUM(revenue)                          AS total_revenue,
            SUM(cost)                             AS total_cost,
            SUM(profit)                           AS total_profit,
            SUM(clicks)                           AS total_clicks,
            SUM(conversions)                      AS total_conversions,
            (SUM(profit) / NULLIF(SUM(cost), 0))  AS roi
        ")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('alias')
                ->orderBy('total_profit', 'desc')
                ->get();

            // contas
            $accountsByAlias = RedtrackReport::selectRaw("
            alias,
            source,
            SUM(revenue)                          AS total_revenue,
            SUM(cost)                             AS total_cost,
            SUM(profit)                           AS total_profit,
            SUM(conversions)                      AS total_conversions,
            SUM(clicks)                           AS total_clicks,
            (SUM(profit) / NULLIF(SUM(cost), 0))  AS roi
        ")
                ->whereBetween('date', [$startDate, $endDate])
                ->groupBy('alias', 'source')
                ->orderBy('total_profit', 'desc')
                ->get()
                ->groupBy('alias');

            // cresximento vs periodo anterior
            $days      = $startDate->diffInDays($endDate) + 1;
            $prevStart = (clone $startDate)->subDays($days);
            $prevEnd   = (clone $endDate)->subDays($days);

            $prevQuery = RedtrackReport::whereBetween('date', [$prevStart, $prevEnd]);
            if (!empty($aliasFilter)) {
                $prevQuery->whereIn('alias', $aliasFilter);
            }
            $prevRevenue = $prevQuery->sum('revenue');

            if ($prevRevenue > 0) {
                $growth = (($totalRevenue - $prevRevenue) / $prevRevenue) * 100;
            } elseif ($totalRevenue > 0) {
                $growth = 100;
            } else {
                $growth = 0;
            }

            // metricas de revenue para hoje, ontem, este mes e mes passado
            $today     = Carbon::today();
            $yesterday = Carbon::yesterday();

            $startOfMonth     = Carbon::now()->startOfMonth();
            $endOfMonth       = Carbon::now()->endOfMonth();
            $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfLastMonth   = Carbon::now()->subMonth()->endOfMonth();

            $todayRevenue     = RedtrackReport::whereDate('date', $today)->sum('revenue');
            $yesterdayRevenue = RedtrackReport::whereDate('date', $yesterday)->sum('revenue');
            $thisMonthRevenue = RedtrackReport::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('revenue');
            $lastMonthRevenue = RedtrackReport::whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->sum('revenue');

            $todayChange = $yesterdayRevenue > 0
                ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
                : 0;

            $monthChange = $lastMonthRevenue > 0
                ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                : 0;

            $revenueMetrics = [
                'today'       => $todayRevenue,
                'yesterday'   => $yesterdayRevenue,
                'todayChange' => $todayChange,
                'month'       => $thisMonthRevenue,
                'lastMonth'   => $lastMonthRevenue,
                'monthChange' => $monthChange,
            ];

            // copa Profit (cache separado, gerado por job) 
            $copaCacheKey = 'copa_profit_' . now()->format('Y-m');
            $copaData     = Cache::get($copaCacheKey);

            if (!$copaData) {
                if (!Cache::has('copa_profit_generating')) {
                    Cache::put('copa_profit_generating', true, 600);
                    dispatch(new \App\Jobs\GenerateCopaProfitCache());
                }

                $copaData = [
                    'podium'        => [],
                    'copiesPodium'  => [],
                    'editorsPodium' => [],
                    'copaYear'      => now()->year,
                    'copaMonths'    => [],
                    'copaPrize'     => 0,
                    'editorPrize'   => 0,
                    'copiePrize'    => 0,
                ];
            }

            // ranking por alias 
            $aliasRanking = (new SquadService())->rankByAlias(4);
            $aliasRanking = $aliasRanking->filter(fn($item) => $item['profit'] > 0);

            // meta mensal 
            $expectedMonthlyProfit = 1000000;
            $currentMonth          = substr(Carbon::now()->locale('en')->monthName, 0, 3);
            $currentMonthProfit    = collect($chartData[$currentMonth] ?? [])->sum('profit');
            $target                = $expectedMonthlyProfit > 0
                ? round(($currentMonthProfit / $expectedMonthlyProfit) * 100, 2)
                : 0;

            return compact(
                'totals',
                'chartData',
                'aliases',
                'maxValue',
                'sources',
                'accountsByAlias',
                'lastUpdate',
                'expectedMonthlyProfit',
                'aliasRanking',
                'target',
                'copaData',
                'growth',
                'revenueMetrics'
            );
        });

        $copaData = $cached['copaData'];

        return view('admin.dashboard', array_merge($cached, [
            'startDate'     => $startDate,
            'endDate'       => $endDate,
            'growth'        => $cached['growth'],
            'revenueMetrics' => $cached['revenueMetrics'],
            'podium'        => $copaData['podium'],
            'copiesPodium'  => $copaData['copiesPodium'],
            'editorsPodium' => $copaData['editorsPodium'],
            'copaYear'      => $copaData['copaYear'],
            'copaMonths'    => $copaData['copaMonths'],
            'copaPrize'     => $copaData['copaPrize'],
            'editorPrize'   => $copaData['editorPrize'],
            'copiePrize'    => $copaData['copiePrize'],
        ]));
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

    public function gestores()
    {
        return view("admin.gestores");
    }

    // Estrutura da Rota Creatives: Ler filtros da request; Normalizar Datas; Carregar Nichos; Carregar agentes; Enviar tudo para a view e Copy/editor MANUALMENTE pelo sistema
    public function creatives(Request $request, string $collaborator = 'IN')
    {
        // 🔹 Validar o parâmetro - vem via URL, não via query string
        $collaborator = in_array($collaborator, ['IN', 'EX']) ? $collaborator : 'IN';

        $type = $request->get('type', 'copywriters');
        $isCopy = $type === 'copywriters';

        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        $nicho = $request->input('nicho', 'TOTAL');
        $source = $request->input('source', 'TOTAL');
        // $agentsFilter = $request->input($isCopy ? 'copywriters' : 'editors');
        $copyFilter = $request->input('copywriters');
        $editorFilter = $request->input('editors');

        if (($copyFilter && !$editorFilter) || (!$copyFilter && $editorFilter)) {
            return back()
                ->withInput()
                ->with('error_cohesion', 'Atenção: Para filtrar por agentes, selecione o Copywriter e o Editor juntos.');
        }



        /* QUERY REAL DOS CRIATIVOS - PARA PUXAR TODOS OS CRIATIVOS DO SISTEMA */

        $query = DB::table('tasks as t')


            ->leftJoin('sub_tasks as st', 'st.task_id', '=', 't.id')
            // ->leftJoin('users as editor', 'editor.id', '=', 't.editor_id')
            ->leftJoin('user_tasks as ut', 'ut.sub_task_id', '=', 'st.id')
            ->leftJoin('users as u', 'u.id', '=', 'ut.user_id')
            ->leftJoin('user_roles as ur', 'ur.user_id', '=', 'u.id')

            // JOIN PARA COLOCAR COPY/EDITOR MANUALEMNTE PELO SISTEMA.
            ->leftJoin('creative_assignments as ca', 'ca.creative_code', '=', 't.code')
            ->leftJoin('users as manual_copy', 'manual_copy.id', '=', 'ca.copywriter_id')
            ->leftJoin('users as manual_editor', 'manual_editor.id', '=', 'ca.editor_id')

            // JOIN PARA PUXAR NICHOS POR ID DE CRIATIVO MM - MEMOMORIA; WL - EMAGRECIMENTO E ETC, VER Nichos.php
            ->leftJoin('nichos as n', function ($join) {
                $join->on(DB::raw('LEFT(t.code, 2)'), '=', 'n.sigla');
            })

            // correcao para criativos VARIACOES APARECEREM NA LISTA + METRICAS CORRETAS
            ->leftJoin(DB::raw("
            (
                SELECT 
                    ad_code,
                    MAX(source) as source,
                    SUM(clicks) as total_clicks,
                    SUM(conversions) as total_conversions,
                    SUM(cost) as total_cost,
                    SUM(profit) as total_profit
                FROM redtrack_reports
                WHERE date BETWEEN '{$startDate}' AND '{$endDate}'
                GROUP BY ad_code
            ) as r
            "), 'r.ad_code', '=', 't.code')


            // removido por duplicacao de join, foi la para cima fazendo o leftjoin com filtro de data junto 
            // ->leftJoin('redtrack_reports as r', 'r.ad_code', '=', 't.code')

            ->leftJoin('validated_creatives as v', 'v.ad', '=', 't.code')

            ->select(

                // NICHO CORRETO
                'n.name as nicho_name',
                't.code as creative_code',

                // alterado para 
                // se existir override manual → usa manual
                // se nao → usa o que vier do redtrack

                // VARIACAO PEGA PELO ID V2,V3, V4, validar
                DB::raw("
                CASE 
                    WHEN t.code REGEXP '(^|[^A-Z])V[0-9]+' THEN 'variation'
                    ELSE 'original'
                END as tipo
            "),


                DB::raw("
                COALESCE(
                    MAX(manual_copy.name),
                    GROUP_CONCAT(DISTINCT CASE 
                        WHEN ur.role_id = 2 THEN u.name 
                    END SEPARATOR ', ')
                ) as copywriter
                "),


                DB::raw("
                COALESCE(
                    MAX(manual_editor.name),
                    GROUP_CONCAT(DISTINCT CASE 
                        WHEN ur.role_id = 3 THEN u.name 
                    END SEPARATOR ', ')
                ) as editor
                "),

                // DB::raw('MAX(r.source) as source'),

                'r.total_clicks',
                'r.total_conversions',
                'r.total_cost',
                'r.total_profit',

                DB::raw('ROUND(r.total_profit / NULLIF(r.total_cost,0),4) as roi_decimal'),

                DB::raw('MAX(v.is_Potential) as potential'),
                DB::raw('MAX(v.is_Validated) as validated')

            );

        // ->whereBetween('r.date', [$startDate, $endDate]); foi la para cima fazendo o leftjoin com filtro de data junto - para os valores de criativos VARIACOES aparecerem

        //  PUXANDO SOMENTES OS ACTIVES + rota de IN - somente criativos dos IN; rota de EX - somente criativo dos EX
        // mostra apenas o criativo da rota atual (IN ou EX) e remove quem esta active = 0
        $query->where(function ($q) use ($collaborator) {
            $q->whereExists(function ($sub) use ($collaborator) {
                $sub->select(DB::raw(1))
                    ->from('users as u_filter')
                    ->join('user_tasks as ut_filter', 'ut_filter.user_id', '=', 'u_filter.id')
                    ->join('sub_tasks as st_filter', 'st_filter.id', '=', 'ut_filter.sub_task_id')
                    ->join('user_roles as ur_filter', 'ur_filter.user_id', '=', 'u_filter.id')
                    ->whereColumn('st_filter.task_id', 't.id')
                    ->where('u_filter.tipo_colaborador', $collaborator)
                    ->where('u_filter.active', 1)
                    ->where('ur_filter.role_id', 2); // Role 2 = Copywriter
            })
                ->orWhereExists(function ($sub) use ($collaborator) {
                    $sub->select(DB::raw(1))
                        ->from('users as u_manual')
                        ->whereColumn('u_manual.id', 'ca.copywriter_id')
                        ->where('u_manual.tipo_colaborador', $collaborator)
                        ->where('u_manual.active', 1);
                });
        });




        // source validar 
        $source = $request->input('source', 'TOTAL');

        $sourceCompare = $source ? strtolower(trim($source)) : 'total';

        if ($sourceCompare !== 'total') {
            $query->whereRaw('LOWER(TRIM(r.source)) LIKE ?', ["%{$sourceCompare}%"]);
        }


        // FILTRO DE COPY E EDITORES FUNCIONANDO
        if (!empty($copyFilter) && !empty($editorFilter)) {
            $copyIds = is_array($copyFilter) ? $copyFilter : [$copyFilter];
            $editorIds = is_array($editorFilter) ? $editorFilter : [$editorFilter];

            // escopo onde o criativo precisa satisfazer as duas condições de copy e editor
            $query->where(function ($q) use ($copyIds, $editorIds) {

                // FILTRO DE COPYWRITER (manual OU puxa automaticamente)
                $q->where(function ($sub) use ($copyIds) {
                    $sub->whereIn('ca.copywriter_id', $copyIds)
                        ->orWhereExists(function ($exists) use ($copyIds) {
                            $exists->select(DB::raw(1))
                                ->from('user_tasks as ut_copy')
                                ->join('sub_tasks as st_copy', 'st_copy.id', '=', 'ut_copy.sub_task_id')
                                ->join('user_roles as ur_copy', 'ur_copy.user_id', '=', 'ut_copy.user_id')
                                ->whereColumn('st_copy.task_id', 't.id')
                                ->whereIn('ut_copy.user_id', $copyIds)
                                ->where('ur_copy.role_id', 2);
                        });
                });

                // FILTRO DE EDITOR (manual OU puxa automaticamente)
                $q->where(function ($sub) use ($editorIds) {
                    $sub->whereIn('ca.editor_id', $editorIds)
                        ->orWhereExists(function ($exists) use ($editorIds) {
                            $exists->select(DB::raw(1))
                                ->from('user_tasks as ut_editor')
                                ->join('sub_tasks as st_editor', 'st_editor.id', '=', 'ut_editor.sub_task_id')
                                ->join('user_roles as ur_editor', 'ur_editor.user_id', '=', 'ut_editor.user_id')
                                ->whereColumn('st_editor.task_id', 't.id')
                                ->whereIn('ut_editor.user_id', $editorIds)
                                ->where('ur_editor.role_id', 3);
                        });
                });
            });
        }




        // FILTRO DE NICHO CORRETO FUNCIONANDO
        if ($nicho && $nicho !== 'TOTAL') {
            $query->where('n.name', $nicho);
        }


        $tipo = $request->input('creation_type');

        if ($tipo && $tipo !== 'TOTAL') {

            if ($tipo === 'variation') {
                $query->whereRaw("t.code REGEXP '(^|[^A-Z])V[0-9]+'");
            }

            // REGEXP '(^|[^A-Z])(^|[^A-Z])V[0-9]+'

            if ($tipo === 'original') {
                $query->whereRaw("t.code NOT REGEXP '(^|[^A-Z])V[0-9]+'");
            }
        }

        // METRICAS GERAIS
        $creatives = $query
            ->groupBy('t.code', 'n.name', 'r.total_clicks', 'r.total_conversions', 'r.total_cost', 'r.total_profit')
            ->orderByDesc('r.total_profit')
            ->get();

        // SEM CRIATIVO REPETIDO E IGUAL DENTRO DA PRODUCAO DOS CRIATIVOS/TABELA
        $creatives = $creatives
            ->reverse()
            ->unique('creative_code')
            ->values()
            ->reverse();



        /* TOP CRIATIVOS  */

        $topCreatives = $creatives
            ->sortByDesc('total_profit') // garante a ordem correta da lista, 3,2 1 critivos mais lucrativos
            ->take(3)
            ->values();
        // $topCreatives = $creatives->take(3)->values();


        /* METRICAS GERAIS  */

        $totalTestado = $creatives->count();

        $totalPotencial = $creatives->where('potential', 1)->count();

        $totalValidados = $creatives->where('validated', 1)->count();

        $winRate = $totalTestado > 0
            ? ($totalValidados / $totalTestado) * 100
            : 0;

        $totals = DB::table('redtrack_reports')
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                SUM(cost) as total_cost,
                SUM(profit) as total_profit,
                SUM(clicks) as total_clicks,
                SUM(conversions) as total_conversions
            ')
            ->first();


        $totalClicks = $totals->total_clicks ?? 0;
        $totalConversions = $totals->total_conversions ?? 0;
        $totalCost = $totals->total_cost ?? 0;
        $totalProfit = $totals->total_profit ?? 0;

        $totalROI = $totalCost > 0
            ? ($totalProfit / $totalCost) * 100
            : 0;



        $bestCreative = $creatives->first();



        /* LISTAS DE FILTROS - COPY/EDITOR */

        $allNiches = Nicho::groupBy('name')->pluck('name');

        // $allCopywriters = User::withRole(2)
        //     ->where('tipo_colaborador', $collaborator)
        //     ->where('active', 1)
        //     ->orderBy('name')
        //     ->pluck('name')
        //     ->toArray();

        $allCopywriters = User::withRole(2)
            ->where('tipo_colaborador', $collaborator)
            ->where('active', 1)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(function ($u) {
                return [
                    'value' => $u->id,
                    'label' => $u->name
                ];
            })
            ->toArray();

        // $allEditors = User::withRole(3)
        //     ->where('active', 1)
        //     ->orderBy('name')
        //     ->pluck('name')
        //     ->toArray();

        $allEditors = User::withRole(3)
            ->where('active', 1)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->map(function ($u) {
                return [
                    'value' => $u->id,
                    'label' => $u->name
                ];
            })
            ->toArray();



        return view("admin.creatives", compact(
            'type',
            'isCopy',
            'startDate',
            'endDate',
            'nicho',
            'source',
            'creatives',
            'allNiches',
            'allCopywriters',
            'allEditors',
            'totalTestado',
            'totalPotencial',
            'totalValidados',
            'winRate',
            'totalClicks',
            'totalConversions',
            'totalCost',
            'totalProfit',
            'totalROI',
            'bestCreative',
            'topCreatives',
            'collaborator'
        ));
    }


    // Funcao para copy/editor MANUALEMENTE pelo sistema
    public function assignCreative(Request $request)
    {
        $creativeCode = $request->creative_code;

        // array criado
        $updateData = [
            'updated_at' => now(),
        ];

        // se veio copywriter no request adiciona ao update
        if ($request->has('copywriter_id') && $request->copywriter_id) {
            $updateData['copywriter_id'] = $request->copywriter_id;
        }

        // se veio editor no request adiciona ao update
        if ($request->has('editor_id') && $request->editor_id) {
            $updateData['editor_id'] = $request->editor_id;
        }


        DB::table('creative_assignments')->updateOrInsert(
            ['creative_code' => $creativeCode],
            $updateData
        );

        // busca o nome do copywriter e editor para return
        $copyName = null;
        $editorName = null;

        if (isset($updateData['copywriter_id'])) {
            $copyName = DB::table('users')->where('id', $request->copywriter_id)->value('name');
        }
        if (isset($updateData['editor_id'])) {
            $editorName = DB::table('users')->where('id', $request->editor_id)->value('name');
        }

        return response()->json([
            'success' => true,
            'copywriter' => $copyName,
            'editor' => $editorName
        ]);
    }




    public function synergyData(Request $request, $type = 'editors')
    {
        $startDate = $request->input('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : Carbon::now()->endOfMonth();

        /**
         * Define o contexto dinamicamente
         */
        $context = match ($type) {
            'copywriters' => [
                'id_param'   => 'copy_id',
                'id_field'   => 'copy_id',
                'name_field' => 'copy_name',
                'label'      => 'copy',
            ],
            default => [
                'id_param'   => 'editor_id',
                'id_field'   => 'editor_id',
                'name_field' => 'editor_name',
                'label'      => 'editor',
            ],
        };


        $userId = $request->input($context['id_param']);

        $agentsService = new AgentsService($startDate, $endDate);
        $duplasData = $agentsService->duoMetrics();

        /**
         * Filtra por editor/copywriter específico
         */
        if ($userId) {
            $duplasData = $duplasData->where($context['id_field'], $userId);
        } else {
            /**
             * Seleciona automaticamente o melhor (maior lucro)
             */
            $best = $duplasData
                ->groupBy($context['id_field'])
                ->map(fn($items) => [
                    'id' => $items->first()->{$context['id_field']},
                    'total_profit' => $items->sum('total_profit'),
                ])
                ->sortByDesc('total_profit')
                ->first();

            if ($best) {
                $duplasData = $duplasData->where(
                    $context['id_field'],
                    $best['id']
                );
            }
        }

        /**
         * Payload final (gráfico de sinergia)
         */
        return response()->json(
            $duplasData->map(fn($d) => [
                'x'        => (float) $d->roi,
                'y'        => (int) $d->total_creatives,
                'label'    => $d->dupla,
                $context['label'] => $d->{$context['name_field']},
                'profit'   => (float) $d->total_profit,
                'roi'      => (float) $d->roi,
                'produced' => (int) $d->total_creatives,
                'testados' => (int)$d->tested
            ])->values()
        );
    }

    /**
     * Mostrar formulário para ativar usuário
     */
    public function createUser()
    {
        $roles = Role::all();
        $inactiveUsers = User::where('active', 0)->get(['id', 'name', 'email']);
        return view('admin.users-create', compact('roles', 'inactiveUsers'));
    }

    /**
     * Ativar usuário existente
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ], [
            'user_id.required' => 'Você deve selecionar um usuário',
            'user_id.exists' => 'O usuário selecionado é inválido',
            'role_id.required' => 'Você deve selecionar uma função',
            'role_id.exists' => 'A função selecionada é inválida',
        ]);

        $user = User::where('id', $validated['user_id'])->where('active', 0)->first();

        if (!$user) {
            return back()->with('error', 'O usuário selecionado já está ativo ou não existe.');
        }

        try {
            // Gerar senha no padrão #Agenciatitan + 4 dígitos
            $plainPassword = '#Agenciatitan' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            // Ativar usuário e definir senha
            $user->update([
                'password' => Hash::make($plainPassword),
                'active' => true,
                'must_change_password' => true,
            ]);

            // Atribuir role (remover roles antigas e atribuir nova)
            $role = Role::find($validated['role_id']);
            $user->roles()->sync([$validated['role_id']]);

            return redirect()->route('admin.users.create')
                ->with('success', "Usuário '{$user->name}' ativado com sucesso!")
                ->with('newUser', [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $plainPassword,
                    'role' => $role->title,
                ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao ativar usuário: ' . $e->getMessage());
        }
    }
}
