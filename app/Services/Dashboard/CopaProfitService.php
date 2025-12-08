<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\RedtrackReport;

class CopaProfitService
{
    private Carbon $startDate;
    private Carbon $endDate;
    private Carbon $quarterStart;
    private Carbon $quarterEnd;
    private array $aliases = ['facebook', 'tiktok', 'google', 'native'];

    /**
     * Construtor do serviço
     * - Se datas forem enviadas → usa as datas
     * - Se NÃO forem enviadas → usa o trimestre atual
     */
    public function __construct(?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        [$defaultStart, $defaultEnd] = $this->getQuarter();

        $this->startDate  = $startDate  ? $startDate->copy()->startOfDay()  : $defaultStart;
        $this->endDate    = $endDate    ? $endDate->copy()->endOfDay()      : $defaultEnd;

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
            'youtube'  => [
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
            } elseif ($alias === 'youtube') {
                $group = 'youtube';
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

            if($key === 'facebook'){
                $grouped[$key]['sku'] = "FB";
            }elseif($key === 'youtube'){
                $grouped[$key]['sku'] = "YT";
            }elseif($key == 'native'){
                $grouped[$key]['sku'] = "NT";
            }
        }

        $result = [];

        foreach ($grouped as $platform => $data) {
            $result[] = [
                'platform'          => $platform,
                'sku'               =>$data['sku'],
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
}
