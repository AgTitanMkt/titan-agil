<?php

namespace App\Services\Dashboard;

use Carbon\Carbon;
use App\Models\RedtrackReport;

/**
 * service dedicado para a Corrida do Profit 2026. Mas, nao ira alterar nada do CopaProfitSercice, pois tem regras e metas diferentes.
 *
 * META: $ 1.500.000 de profit
 * PREMIACAO: $ 100.000 para o squad vencedor
 * SQUADS: YouTube (YTA), Facebook (FBR), Native (NTE)
 */
class CorridaProfitService
{
    private Carbon $startDate;
    private Carbon $endDate;

    // meta e premiacao fixas
    public const META_PROFIT   = 1_500_000;
    public const PREMIO_CORRIDA = 100_000;

    // mapeamento dos squads
    private array $squads = [
        'youtube' => ['label' => 'YouTube', 'sku' => 'YTA', 'aliases' => ['youtube', 'google']],
        'facebook' => ['label' => 'Facebook', 'sku' => 'FBR', 'aliases' => ['facebook']],
        'native'  => ['label' => 'Native',   'sku' => 'NTE', 'aliases' => []],  // tudo que não é YT/FB
    ];

    /**
     * construtor
     * - Se $startDate e $endDate forem passados -> usa essas datas
     * - senao usa o ano corrente (1 Jan2026 – hoje2026) 
     */
    public function __construct(
        ?Carbon $startDate = null,
        ?Carbon $endDate   = null
    ) {
        $this->startDate = $startDate
            ? $startDate->copy()->startOfDay()
            : Carbon::create(now()->year, 1, 1)->startOfDay();

        $this->endDate = $endDate
            ? $endDate->copy()->endOfDay()
            : now()->endOfDay();
    }

    /* PROFIT TOTAL POR SQUAD (agrupado por alias)*/
    private function getProfitBySquad(): array
    {
        $rows = RedtrackReport::selectRaw("
                LOWER(alias) as alias,
                SUM(profit)  as total_profit
            ")
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('alias')
            ->get();

        // inicializa os squads com zero
        $result = [
            'youtube'  => 0.0,
            'facebook' => 0.0,
            'native'   => 0.0,
        ];

        foreach ($rows as $row) {
            $alias = strtolower($row->alias);

            if (in_array($alias, ['youtube', 'google'])) {
                $result['youtube'] += (float) $row->total_profit;
            } elseif ($alias === 'facebook') {
                $result['facebook'] += (float) $row->total_profit;
            } else {
                $result['native'] += (float) $row->total_profit;
            }
        }

        return $result;
    }

    /* PODIO DOS SQUADS —> ordenado por profit desc */
    private function buildPodium(): array
    {
        $profits = $this->getProfitBySquad();

        // monta array com dados
        $squads = [];
        foreach ($this->squads as $key => $info) {
            $profit = $profits[$key] ?? 0.0;
            $squads[] = [
                'squad'    => $key,
                'label'    => $info['label'],
                'sku'      => $info['sku'],
                'profit'   => $profit,
                'progress' => self::META_PROFIT > 0
                    ? min(round(($profit / self::META_PROFIT) * 100, 2), 100)
                    : 0,
                'meta_atingida' => $profit >= self::META_PROFIT,
            ];
        }

        // ordena pelo maior profit
        usort($squads, fn($a, $b) => $b['profit'] <=> $a['profit']);

        // adiciona rank
        foreach ($squads as $i => &$squad) {
            $squad['rank'] = $i + 1;
        }
        unset($squad);

        return $squads;
    }

    /* TOTAIS GERAIS DO PERIODO */
    private function getTotalProfit(): float
    {
        return (float) RedtrackReport::whereBetween('date', [$this->startDate, $this->endDate])
            ->sum('profit');
    }

    /* PROGRESSO GERAL DA CORRIDA (soma de todos os squads vs meta) */
    private function getCorridaProgress(float $totalProfit): float
    {
        return self::META_PROFIT > 0
            ? min(round(($totalProfit / self::META_PROFIT) * 100, 2), 100)
            : 0;
    }

    /*  MAKE PRINCIPAL —> retorna o payload COMPLETO para a view */
    public function make(): array
    {
        $podium      = $this->buildPodium();
        $totalProfit = $this->getTotalProfit();
        $progress    = $this->getCorridaProgress($totalProfit);

        return [
            // podio ranqueado
            'podium'         => $podium,

            // totais gerais
            'totalProfit'    => $totalProfit,
            'corridaProgress' => $progress,

            // meta e premiacao
            'metaProfit'     => self::META_PROFIT,
            'premioCorreida' => self::PREMIO_CORRIDA,

            // datas do periodo
            'startDate'      => $this->startDate,
            'endDate'        => $this->endDate,

            // ano da corrida
            'corridaYear'    => $this->startDate->year,

            // ultima atualizacao dos dados
            'lastUpdate'     => RedtrackReport::max('updated_at'),
        ];
    }
}