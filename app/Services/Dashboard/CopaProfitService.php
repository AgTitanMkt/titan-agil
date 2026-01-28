<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\RedtrackReport;

class CopaProfitService
{
    private Carbon $startDate;
    private Carbon $endDate;
    private int $dayOfStart;
    private Carbon $quarterStart;
    private Carbon $quarterEnd;
    private array $aliases = ['facebook', 'tiktok', 'google', 'native'];

    /**
     * Construtor do serviço
     * - Se datas forem enviadas → usa as datas
     * - Se NÃO forem enviadas → usa o trimestre atual
     * - dayOfStart é a data de inicio no ciclo, isso evita que ja no dia primeiro rode para o novo 
     *      ciclo e perca os dados do ciclo anterior, isso dara uma gordura para obter os dados.
     */
    public function __construct(
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        ?int $cycle = null,
        ?int $year = null
    ) {
        // Se ciclo foi informado → ignora datas manuais
        if ($cycle) {
            [$cycleStart, $cycleEnd] = $this->getCyclePeriod(
                $cycle,
                $year ?? now()->year
            );

            $this->startDate = $cycleStart;
            $this->endDate   = $cycleEnd;

            $this->quarterStart = $cycleStart;
            $this->quarterEnd   = $cycleEnd;

            return;
        }

        // Comportamento atual (default)
        [$defaultStart, $defaultEnd] = $this->getQuarter();

        $this->startDate  = $startDate ? $startDate->copy()->startOfDay() : $defaultStart;
        $this->endDate    = $endDate   ? $endDate->copy()->endOfDay()     : $defaultEnd;

        $this->quarterStart = $defaultStart;
        $this->quarterEnd   = $defaultEnd;
    }


    /* ============================================================
       TRIMESTRE AUTOMÁTICO
    ============================================================ */
    private function getQuarter(): array
    {
        $m = now()->month;
        $y = now()->year;

        if ($m <= 3) {
            return [
                Carbon::create($y, 1, 1)->startOfMonth(),
                Carbon::create($y, 3, 1)->endOfMonth(),
            ];
        }

        if ($m <= 6) {
            return [
                Carbon::create($y, 4, 1)->startOfMonth(),
                Carbon::create($y, 6, 1)->endOfMonth(),
            ];
        }

        if ($m <= 9) {
            return [
                Carbon::create($y, 7, 1)->startOfMonth(),
                Carbon::create($y, 9, 1)->endOfMonth(),
            ];
        }

        return [
            Carbon::create($y, 10, 1)->startOfMonth(),
            Carbon::create($y, 12, 1)->endOfMonth(),
        ];
    }

    /* ============================================================
       MÉTRICAS GERAIS DO PERÍODO
    ============================================================ */
    private function getTotals()
    {
        $q = RedtrackReport::whereBetween('date', [$this->startDate, $this->endDate]);

        $cost = $q->sum('cost');
        $profit = $q->sum('profit');

        return [
            'cost'   => $cost,
            'profit' => $profit,
            'roi'    => $cost > 0 ? $profit / $cost : 0,
        ];
    }

    /* ============================================================
       GRÁFICO MENSAL
    ============================================================ */
    private function getMonthlyChart()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $monthly = RedtrackReport::selectRaw("
                MONTH(date) as m,
                DATE_FORMAT(date,'%b') as month_name,
                LOWER(alias) as alias,
                SUM(profit) as profit
            ")
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('m', 'month_name', 'alias')
            ->get();

        $chart = [];
        foreach ($months as $m) {
            $chart[$m] = ['facebook' => 0, 'tiktok' => 0, 'google' => 0, 'native' => 0];
        }

        foreach ($monthly as $row) {
            $alias = in_array($row->alias, ['facebook', 'tiktok', 'google'])
                ? $row->alias
                : 'native';

            $chart[$row->month_name][$alias] += $row->profit;
        }

        $max = max(array_map(fn($m) => max($m), $chart));

        return [$chart, $max > 0 ? $max : 1];
    }

    /* ============================================================
       PERFORMANCE POR ALIAS
    ============================================================ */
    private function getAliasMetrics()
    {
        return RedtrackReport::selectRaw("
                alias,
                SUM(cost) as total_cost,
                SUM(profit) as total_profit,
                SUM(clicks) as total_clicks,
                SUM(conversions) as total_conversions,
                (SUM(profit)/NULLIF(SUM(cost),0)) as roi
            ")
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('alias')
            ->orderBy('alias')
            ->get();
    }

    private function getAccountsByAlias()
    {
        return RedtrackReport::selectRaw("
                alias,
                source,
                SUM(cost) as total_cost,
                SUM(profit) as total_profit,
                SUM(conversions) as total_conversions,
                SUM(clicks) as total_clicks,
                (SUM(profit)/NULLIF(SUM(cost),0)) as roi
            ")
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('alias', 'source')
            ->orderBy('total_profit', 'desc')
            ->get()
            ->groupBy('alias');
    }

    /* ============================================================
       PÓDIO DOS SQUADS
    ============================================================ */
    private function getSquadPodium()
    {
        $squadService = new SquadService($this->quarterStart, $this->quarterEnd);
        $top = $squadService->profit();

        return collect($top)->map(function ($row, $i) {
            $names = [
                'facebook' => 'Facebook Squad',
                'tiktok' => 'TikTok Squad',
                'native' => 'Native Squad',
                'google_dime' => 'Google – Dime',
                'google_ary' => 'Google – Ary',
                'google_david' => 'Google – David',
            ];

            return [
                'rank'      => $i + 1,
                'squad'     => $row['squad'],
                'name'      => $names[$row['squad']] ?? $row['squad'],
                'profit'    => $row['profit'],
                'roi'       => $row['roi']
            ];
        });
    }

    /* ============================================================
       PÓDIO COPIES / EDITORES
    ============================================================ */
    private function getAgentsPodiums()
    {
        $agents = new AgentsService($this->quarterStart, $this->quarterEnd);
        $copies = $agents->rankCopies(3);
        $editors = $agents->rankEditors(3);

        return [
            'copies' => $copies->map(fn($r, $i) => [
                'rank'   => $i + 1,
                'avatar' => $this->initials($r->user_name), // INICIAIS
                'name'   => $r->user_name,
                'profit' => $r->total_profit,
            ]),

            'editors' => $editors->map(fn($r, $i) => [
                'rank'   => $i + 1,
                'avatar' => $this->initials($r->user_name), // INICIAIS
                'name'   => $r->user_name,
                'profit' => $r->total_profit,
            ]),
        ];
    }


    /* ============================================================
       MÉTODO FINAL — MONTA O OBJETO PARA A VIEW
    ============================================================ */
    public function make(): array
    {
        [$chartData, $maxValue] = $this->getMonthlyChart();

        // Nome dos meses
        $months = [
            $this->quarterStart->translatedFormat('F'),
            $this->quarterStart->copy()->addMonth()->translatedFormat('F'),
            $this->quarterEnd->translatedFormat('F'),
        ];
        return [
            'totals'            => $this->getTotals(),
            'startDate'         => $this->startDate,
            'endDate'           => $this->endDate,
            'chartData'         => $chartData,
            'maxValue'          => $maxValue,
            'aliases'           => $this->aliases,
            'sources'           => $this->getAliasMetrics(),
            'accountsByAlias'   => $this->getAccountsByAlias(),
            'lastUpdate'        => RedtrackReport::max('updated_at'),
            'podium'            => $this->getSquadPodium(),
            'copiesPodium'      => $this->getAgentsPodiums()['copies'],
            'editorsPodium'     => $this->getAgentsPodiums()['editors'],
            'expectedMonthlyProfit' => 1000000,

            // ⬇️ Dados adicionais para o header
            'copaYear'          => $this->quarterStart->year,
            'copaMonths'        => $months,
            'copaPrize'         => 130000, // pode vir do banco depois
            'editorPrize'       => 10000,
            'copiePrize'        => 20000,
        ];
    }


    private function initials(string $name): string
    {
        $parts = explode(' ', trim($name));
        $initials = '';

        foreach ($parts as $p) {
            if (strlen($p) > 0) {
                $initials .= strtoupper($p[0]);
            }
        }

        return substr($initials, 0, 3); // máximo 3 letras
    }

    public function getPlatformsMetrics()
    {
        $metrics = $this->getAliasMetrics();
        return $metrics;
    }

    /**
     * Retorna métricas por SOURCE (sem agrupar por alias)
     * Ordenado pelo MAIOR total_profit
     */
    public function getPlatformsMetricsSources()
    {
        $metrics = RedtrackReport::selectRaw("
            source,
            LOWER(alias) as alias,
            SUM(cost) as total_cost,
            SUM(profit) as total_profit,
            SUM(clicks) as total_clicks,
            SUM(conversions) as total_conversions,
            (SUM(profit)/NULLIF(SUM(cost),0)) as roi
        ")
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('source', 'alias')
            ->orderBy('total_profit', 'desc')
            ->get();
        $result = [];

        foreach ($metrics as $m) {

            // ---------------------------
            // 🚀 NORMALIZAÇÃO DO ALIAS
            // ---------------------------

            $alias = $m->alias;

            // FACEBOOK
            if ($alias === 'facebook') {
                $alias = 'facebook';
            }

            // GOOGLE/YOUTUBE
            elseif (str_contains($alias, 'google') || str_contains($alias, 'youtube')) {
                $alias = 'google'; // seu front usa GO
            }

            // NATIVE (TODOS RESTANTES)
            else {
                $alias = 'native';
            }

            // ---------------------------
            // Monta o resultado
            // ---------------------------
            $result[] = [
                'source'            => $m->source,
                'alias'             => $alias,
                'total_cost'        => (float) $m->total_cost,
                'total_profit'      => (float) $m->total_profit,
                'total_clicks'      => (int)   $m->total_clicks,
                'total_conversions' => (int)   $m->total_conversions,
                'roi'               => $m->total_cost > 0
                    ? $m->total_profit / $m->total_cost
                    : 0,
            ];
        }

        return $result;
    }



    /**
     * lista as metricas por plataforma agrupando o native
     */
    public function getPlatformsMetricsGroup()
    {
        $metrics = $this->getPlatformsMetrics();

        // Agora é array puro, não Collection
        $grouped = [
            'facebook' => [
                'total_cost'        => 0,
                'total_profit'      => 0,
                'total_clicks'      => 0,
                'total_conversions' => 0,
                'roi'               => 0,
            ],
            'google'  => [
                'total_cost'        => 0,
                'total_profit'      => 0,
                'total_clicks'      => 0,
                'total_conversions' => 0,
                'roi'               => 0,
            ],
            'native'   => [
                'total_cost'        => 0,
                'total_profit'      => 0,
                'total_clicks'      => 0,
                'total_conversions' => 0,
                'roi'               => 0,
            ],
        ];

        foreach ($metrics as $metric) {
            $alias = strtolower($metric->alias);

            if ($alias === 'facebook') {
                $group = 'facebook';
            } elseif ($alias === 'google') {
                $group = 'google';
            } else {
                $group = 'native';
            }

            // Soma valores normalmente (agora funciona)
            $grouped[$group]['total_cost']        += (float) $metric->total_cost;
            $grouped[$group]['total_profit']      += (float) $metric->total_profit;
            $grouped[$group]['total_clicks']      += (int)   $metric->total_clicks;
            $grouped[$group]['total_conversions'] += (int)   $metric->total_conversions;
        }

        // Calcula ROI individual
        foreach ($grouped as $key => $row) {
            if ($row['total_cost'] > 0) {
                $grouped[$key]['roi'] = $row['total_profit'] / $row['total_cost'];
            } else {
                $grouped[$key]['roi'] = 0;
            }

            if ($key === 'facebook') {
                $grouped[$key]['sku'] = "FB";
            } elseif ($key === 'google') {
                $grouped[$key]['sku'] = "GO";
            } elseif ($key == 'native') {
                $grouped[$key]['sku'] = "NT";
            }
        }

        $result = [];

        foreach ($grouped as $platform => $data) {
            $result[] = [
                'platform'          => $platform,
                'sku'               => $data['sku'],
                'total_cost'        => $data['total_cost'],
                'total_profit'      => $data['total_profit'],
                'total_clicks'      => $data['total_clicks'],
                'total_conversions' => $data['total_conversions'],
                'roi'               => $data['roi'],
            ];
        }

        // Ordena pelo maior profit
        usort($result, function ($a, $b) {
            return $b['total_profit'] <=> $a['total_profit'];
        });

        return $result;
    }

    public static function AgentsMetrics($startDate, $endDate, $agents = null)
    {
        /**
         * ============================================================
         * 📌 REGRA GERAL DA FUNÇÃO
         * ============================================================
         *
         * Para CADA criativo (task):
         *
         * 🔹 FILTRO DE PERÍODO
         * - Se EXISTE RedTrack no período → filtra por rr.redtrack_date
         * - Se NÃO EXISTE RedTrack no período → filtra por t.created_at
         *
         * 🔹 PRODUZIDO
         * - Se teve RedTrack no período:
         *     → produzido = 1 SOMENTE se a PRIMEIRA aparição no RedTrack
         *       (fr.first_redtrack_date) estiver dentro do período
         *
         * - Se NÃO teve RedTrack no período:
         *     → produzido = 1 SOMENTE se t.created_at estiver dentro do período
         *
         * Caso contrário → produzido = 0
         */

        /**
         * ============================================================
         * 1️⃣ PRIMEIRA DATA HISTÓRICA NO REDTRACK (independe do período)
         * ============================================================
         */
        $firstDateSub = DB::table('redtrack_reports')
            ->selectRaw('LOWER(ad_code) AS ad_code_norm, MIN(date) AS first_redtrack_date')
            ->groupBy(DB::raw('LOWER(ad_code)'));

        /**
         * ============================================================
         * 2️⃣ REDTRACK AGREGADO NO PERÍODO
         * ============================================================
         * Se não existir linha aqui → criativo NÃO FOI TESTADO no período
         */
        $rrAgg = DB::table('redtrack_reports')
            ->selectRaw("
            LOWER(ad_code) AS ad_code_norm,
            MAX(date) AS redtrack_date,
            SUM(clicks) AS total_clicks,
            SUM(conversions) AS total_conversions,
            SUM(cost) AS total_cost,
            SUM(profit) AS total_profit
        ")
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('LOWER(ad_code)'));

        /**
         * ============================================================
         * 3️⃣ QUERY PRINCIPAL
         * ============================================================
         */
        $query = DB::table('user_tasks AS ut')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('sub_tasks AS st', 'st.id', '=', 'ut.sub_task_id')
            ->join('tasks AS t', 't.id', '=', 'st.task_id')
            ->join('nichos AS n', 'n.id', '=', 't.nicho')

            /* 🔗 RedTrack agregado (performance no período) */
            ->leftJoinSub($rrAgg, 'rr', function ($join) {
                $join->on('rr.ad_code_norm', '=', DB::raw('LOWER(t.code)'));
            })

            /* 🕰️ Primeira aparição histórica no RedTrack */
            ->leftJoinSub($firstDateSub, 'fr', function ($join) {
                $join->on('fr.ad_code_norm', '=', DB::raw('LOWER(t.code)'));
            })

            /* 🧪 Validação manual */
            ->leftJoin('validated_creatives AS vc', function ($join) {
                $join->on('vc.subtask_id', '=', 'st.id');
            })

            /**
             * ============================================================
             * ✅ FILTRO DE PERÍODO (REGRA CORRETA)
             * ============================================================
             */
            ->where(function ($q) use ($startDate, $endDate) {

                // 1️⃣ Criativos COM performance no período
                $q->whereBetween('rr.redtrack_date', [$startDate, $endDate])

                    // 2️⃣ Criativos SEM performance no período
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->whereNull('rr.redtrack_date')
                            ->whereBetween('t.created_at', [$startDate, $endDate]);
                    });
            })

            /* 🎯 Filtro opcional por agente */
            ->when($agents, function ($q) use ($agents) {
                $q->whereIn('u.name', (array) $agents);
            })

            /**
             * ============================================================
             * 📊 SELECT FINAL
             * ============================================================
             */
            ->selectRaw("
            u.id AS user_id,
            u.name AS agent_name,

            t.id AS task_id,
            t.code,
            n.id AS nicho_id,
            n.name AS nicho_name,

            rr.redtrack_date,
            fr.first_redtrack_date,

            COALESCE(rr.total_clicks, 0) AS total_clicks,
            COALESCE(rr.total_conversions, 0) AS total_conversions,
            COALESCE(rr.total_cost, 0) AS total_cost,
            COALESCE(rr.total_profit, 0) AS total_profit,

            CASE
                WHEN COALESCE(rr.total_cost, 0) > 0
                THEN COALESCE(rr.total_profit, 0) / rr.total_cost
                ELSE 0
            END AS roi,

            /* 📦 PRODUZIDO (CORRIGIDO) */
            CASE
                -- Criativo testado no período
                WHEN rr.ad_code_norm IS NOT NULL THEN
                    CASE
                        WHEN DATE(fr.first_redtrack_date) BETWEEN DATE(?) AND DATE(?) THEN 1
                        ELSE 0
                    END
                -- Criativo ainda não testado no período
                ELSE
                    CASE
                        WHEN DATE(t.created_at) BETWEEN DATE(?) AND DATE(?) THEN 1
                        ELSE 0
                    END
            END AS produzido,

            /* 🧪 TESTADO */
            CASE WHEN rr.ad_code_norm IS NULL THEN 0 ELSE 1 END AS testados,

            /* ⚡ EM POTENCIAL */
            CASE WHEN vc.is_potential = 1 THEN 1 ELSE 0 END AS em_potencial,

            /* 🔥 VALIDADO */
            CASE WHEN vc.is_validated = 1 THEN 1 ELSE 0 END AS validado
        ")

            /* 🔐 Bindings do CASE produzido */
            ->addBinding([
                $startDate,
                $endDate,
                $startDate,
                $endDate
            ], 'select')

            /**
             * ============================================================
             * 📦 GROUP BY
             * ============================================================
             */
            ->groupBy(
                'u.id',
                'u.name',
                't.id',
                't.code',
                'n.id',
                'n.name',
                'rr.redtrack_date',
                'fr.first_redtrack_date',
                'rr.total_clicks',
                'rr.total_conversions',
                'rr.total_cost',
                'rr.total_profit',
                'rr.ad_code_norm',
                'vc.is_potential',
                'vc.is_validated'
            );

        /**
         * ============================================================
         * 📤 RETORNO
         * ============================================================
         */
        return $query->get()->groupBy('user_id');
    }





    private function getCyclePeriod(int $cycle, int $year): array
    {
        return match ($cycle) {
            1 => [
                Carbon::create($year, 1, 1)->startOfMonth(),
                Carbon::create($year, 3, 1)->endOfMonth(),
            ],
            2 => [
                Carbon::create($year, 4, 1)->startOfMonth(),
                Carbon::create($year, 6, 1)->endOfMonth(),
            ],
            3 => [
                Carbon::create($year, 7, 1)->startOfMonth(),
                Carbon::create($year, 9, 1)->endOfMonth(),
            ],
            4 => [
                Carbon::create($year, 10, 1)->startOfMonth(),
                Carbon::create($year, 12, 1)->endOfMonth(),
            ],
            default => throw new \InvalidArgumentException('Ciclo inválido'),
        };
    }
}
