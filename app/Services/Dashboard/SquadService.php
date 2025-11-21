<?php

namespace App\Services\Dashboard;

use App\Models\RedtrackReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SquadService
{
    /**
     * @var string top|all|facebook|tiktok|native|google|google_dime|google_ary|google_david
     */
    private string $squad;
    private Carbon $startAt;
    private Carbon $finishAt;

    public function __construct(
        $startAt = null,
        $finishAt = null,
        string $squad = 'top'
    ) {
        $this->squad    = $squad;
        $this->startAt  = $startAt  ?? Carbon::now()->startOfMonth();
        $this->finishAt = $finishAt ?? Carbon::now()->endOfMonth();
    }

    /**
     * Retorna métricas de profit conforme o tipo do squad.
     *
     * - top  : top 3 squads (facebook, tiktok, native, google_* se existirem)
     * - all  : todos os squads agregados
     * - google: sempre segmentado em google_dime, google_ary, google_david
     * - outro: um squad específico (facebook, tiktok, native, google_*)
     */
    public function profit()
    {
        if ($this->squad === 'top') {
            return $this->topSquads();
        }

        if ($this->squad === 'all') {
            return $this->allSquads();
        }

        if ($this->squad === 'google') {
            return $this->googleSegments();
        }

        return $this->oneSquad($this->squad);
    }

    /* =========================================================================
     *  TOP e ALL
     * ========================================================================= */

    /**
     * Top 3 squads (google sempre segmentado em dime/ary/david)
     */
    private function topSquads(): Collection
    {
        $rows = $this->baseRowsGroupedByAliasAndSource();

        $grouped = $this->groupRowsBySquad($rows);

        return $grouped
            ->sortByDesc('profit')
            ->take(3)
            ->values();
    }

    /**
     * Todos os squads agregados (google segmentado)
     */
    private function allSquads(): Collection
    {
        $rows = $this->baseRowsGroupedByAliasAndSource();

        return $this->groupRowsBySquad($rows)
            ->sortByDesc('profit')
            ->values();
    }

    /**
     * Query base: agrega por alias+source para depois redistribuir por squad.
     */
    private function baseRowsGroupedByAliasAndSource(): Collection
    {
        return RedtrackReport::whereBetween('date', [
            $this->startAt,
            $this->finishAt,
        ])
            ->selectRaw('alias, source, SUM(clicks) AS clicks, SUM(conversions) AS conversions, SUM(cost) AS cost, SUM(profit) AS profit')
            ->groupBy('alias', 'source')
            ->get();
    }

    /**
     * Agrupa os rows em squads finais:
     * facebook, tiktok, native, google_dime, google_ary, google_david
     */
    private function groupRowsBySquad(Collection $rows): Collection
    {
        $groups = [];

        foreach ($rows as $row) {
            $squad = $this->detectSquad($row->alias, $row->source);

            // alias google sem Dime/Ary/David é descartado
            if ($squad === null) {
                continue;
            }

            if (!isset($groups[$squad])) {
                $groups[$squad] = [
                    'squad'       => $squad,
                    'clicks'      => 0,
                    'conversions' => 0,
                    'cost'        => 0,
                    'profit'      => 0,
                    'start_at'    => $this->startAt->toDateString(),
                    'finish_at'   => $this->finishAt->toDateString(),
                ];
            }

            $groups[$squad]['clicks']      += (int) $row->clicks;
            $groups[$squad]['conversions'] += (int) $row->conversions;
            $groups[$squad]['cost']        += (float) $row->cost;
            $groups[$squad]['profit']      += (float) $row->profit;
        }

        // Calcula ROI
        foreach ($groups as &$g) {
            $g['roi'] = $g['cost'] > 0
                ? round($g['profit'] / $g['cost'], 4)
                : 0;
        }

        return collect(array_values($groups));
    }

    /**
     * Detecta em qual squad final o registro entra.
     *
     * facebook, tiktok, native, google_dime, google_ary, google_david
     * Retorna null para descartar (google sem Dime/Ary/David).
     */
    private function detectSquad(?string $alias, ?string $source): ?string
    {
        $alias  = strtolower((string) $alias);
        $source = (string) $source;

        if ($alias === 'facebook') {
            return 'facebook';
        }

        if ($alias === 'tiktok') {
            return 'tiktok';
        }

        if ($alias === 'google') {
            if (stripos($source, 'Dime') !== false) {
                return 'google_dime';
            }
            if (stripos($source, 'Ary') !== false) {
                return 'google_ary';
            }
            if (stripos($source, 'David') !== false) {
                return 'google_david';
            }

            // google sem Dime/Ary/David é descartado
            return null;
        }

        // qualquer outro alias vira native
        return 'native';
    }

    /* =========================================================================
     *  SQUADS ESPECÍFICOS
     * ========================================================================= */

    /**
     * Retorna um squad específico:
     * facebook, tiktok, native, google_dime, google_ary, google_david
     */
    private function oneSquad(string $squad): array
    {
        switch ($squad) {
            case 'facebook':
            case 'tiktok':
                $query = RedtrackReport::whereBetween('date', [
                    $this->startAt,
                    $this->finishAt,
                ])
                    ->whereRaw('LOWER(alias) = ?', [$squad]);
                break;

            case 'native':
                $query = RedtrackReport::whereBetween('date', [
                    $this->startAt,
                    $this->finishAt,
                ])
                    ->whereRaw("LOWER(alias) NOT IN ('facebook','tiktok','google')");
                break;

            case 'google_dime':
                return $this->oneGoogleSegment('Dime', 'google_dime');

            case 'google_ary':
                return $this->oneGoogleSegment('Ary', 'google_ary');

            case 'google_david':
                return $this->oneGoogleSegment('David', 'google_david');

            default:
                // squad desconhecido → retorna zerado
                return [
                    'squad'       => $squad,
                    'start_at'    => $this->startAt->toDateString(),
                    'finish_at'   => $this->finishAt->toDateString(),
                    'clicks'      => 0,
                    'conversions' => 0,
                    'cost'        => 0.0,
                    'profit'      => 0.0,
                    'roi'         => 0.0,
                ];
        }

        $cost   = (clone $query)->sum('cost');
        $profit = (clone $query)->sum('profit');

        return [
            'squad'       => $squad,
            'start_at'    => $this->startAt->toDateString(),
            'finish_at'   => $this->finishAt->toDateString(),
            'clicks'      => (clone $query)->sum('clicks'),
            'conversions' => (clone $query)->sum('conversions'),
            'cost'        => $cost,
            'profit'      => $profit,
            'roi'         => $cost > 0 ? round($profit / $cost, 4) : 0,
        ];
    }

    /**
     * Retorna os 3 segmentos do google:
     * google_dime, google_ary, google_david
     */
    private function googleSegments(): array
    {
        return [
            $this->oneGoogleSegment('Dime', 'google_dime'),
            $this->oneGoogleSegment('Ary',  'google_ary'),
            $this->oneGoogleSegment('David', 'google_david'),
        ];
    }

    /**
     * Consolida um segmento específico do google (Dime/Ary/David).
     */
    private function oneGoogleSegment(string $keyword, string $label): array
    {
        $query = RedtrackReport::whereBetween('date', [
            $this->startAt,
            $this->finishAt,
        ])
            ->whereRaw("LOWER(alias) = 'google'")
            ->where('source', 'LIKE', '%' . $keyword . '%');

        $cost   = (clone $query)->sum('cost');
        $profit = (clone $query)->sum('profit');

        return [
            'squad'       => $label,
            'start_at'    => $this->startAt->toDateString(),
            'finish_at'   => $this->finishAt->toDateString(),
            'clicks'      => (clone $query)->sum('clicks'),
            'conversions' => (clone $query)->sum('conversions'),
            'cost'        => $cost,
            'profit'      => $profit,
            'roi'         => $cost > 0 ? round($profit / $cost, 4) : 0,
        ];
    }
    /**
     * Rank por alias puro:
     * facebook, tiktok, google, native
     * (NÃO segmenta google e NÃO mistura platforms dentro de native)
     *
     * @param int $limit
     * @return Collection
     */
    public function rankByAlias(int $limit = 0): Collection
    {
        $rows = RedtrackReport::whereBetween('date', [
            $this->startAt,
            $this->finishAt,
        ])
            ->selectRaw("
            LOWER(alias) AS alias,
            SUM(clicks) AS clicks,
            SUM(conversions) AS conversions,
            SUM(cost) AS cost,
            SUM(profit) AS profit
        ")
            ->groupBy('alias')
            ->orderBy('profit', 'desc')
            ->get();

        // Ajuste: google fica como 'google', native apenas 'native'
        $final = collect($rows)->map(function ($row) {

            // normaliza alias
            $alias = strtolower($row->alias);

            // tudo que não é facebook/tiktok/google vira native
            if (!in_array($alias, ['facebook', 'tiktok', 'google'])) {
                $alias = 'native';
            }

            return [
                'alias'       => $alias,
                'clicks'      => (int) $row->clicks,
                'conversions' => (int) $row->conversions,
                'cost'        => (float) $row->cost,
                'profit'      => (float) $row->profit,
                'roi'         => $row->cost > 0 ? round($row->profit / $row->cost, 4) : 0,
                'start_at'    => $this->startAt->toDateString(),
                'finish_at'   => $this->finishAt->toDateString(),
            ];
        });

        // agora precisamos agrupar native (porque vários aliases "estranhos" viram native)
        $grouped = $final
            ->groupBy('alias')
            ->map(function ($items, $alias) {

                return [
                    'alias'       => $alias,
                    'clicks'      => $items->sum('clicks'),
                    'conversions' => $items->sum('conversions'),
                    'cost'        => $items->sum('cost'),
                    'profit'      => $items->sum('profit'),
                    'roi'         => $items->sum('cost') > 0
                        ? round($items->sum('profit') / $items->sum('cost'), 4)
                        : 0,
                ];
            })
            ->sortByDesc('profit')
            ->values();

        if ($limit > 0) {
            return $grouped->take($limit);
        }

        return $grouped;
    }
}
