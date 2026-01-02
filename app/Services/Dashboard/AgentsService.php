<?php

namespace App\Services\Dashboard;

use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AgentsService
{
    private Carbon $startAt;
    private Carbon $finishAt;

    public function __construct(
        $startAt = null,
        $finishAt = null
    ) {
        $this->startAt  = $startAt  ?? Carbon::now()->startOfMonth();
        $this->finishAt = $finishAt ?? Carbon::now()->endOfMonth();
    }

    /**
     * Ranking genérico (COPY ou EDITOR)
     * $roleId → ex: copy = 2, editor = 3
     */
    public function rank(int $roleId, $limit = null): Collection
    {
        $sub = $this->baseSubQuery($roleId);
        $baseQuery = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'user_name',
                DB::raw('COUNT(DISTINCT creative_code) AS total_creatives'),
                DB::raw('SUM(rt_clicks) AS total_clicks'),
                DB::raw('SUM(rt_conversions) AS total_conversions'),
                DB::raw('SUM(rt_cost) AS total_cost'),
                DB::raw('SUM(rt_profit) AS total_profit'),
                DB::raw('SUM(rt_revenue) AS total_revenue'),
                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS total_roi')
            )
            ->groupBy('user_id', 'user_name')
            ->orderBy('total_profit', 'DESC');

        $rows = $limit ? $baseQuery->limit($limit)->get() : $baseQuery->get();

        return $rows;
    }

    /**
     * Retorna detalhes de um talento
     * Criativos + métricas
     */
    public function talentDetails(int $roleId, int $userId): array
    {
        $sub = $this->baseSubQuery($roleId);

        // MÉTRICAS GERAIS
        $summary = DB::query()
            ->fromSub($sub, 'c')
            ->select(
                'user_id',
                'user_name',
                DB::raw('COUNT(DISTINCT creative_code) AS creatives'),
                DB::raw('SUM(rt_clicks) AS clicks'),
                DB::raw('SUM(rt_conversions) AS conversions'),
                DB::raw('SUM(rt_cost) AS cost'),
                DB::raw('SUM(rt_profit) AS profit'),
                DB::raw('SUM(rt_revenue) AS revenue'),
                DB::raw('ROUND(SUM(rt_profit) / NULLIF(SUM(rt_cost), 0), 4) AS roi')
            )
            ->where('user_id', $userId)
            ->groupBy('user_id', 'user_name')
            ->first();

        // CRIATIVOS DETALHADOS
        $creatives = DB::query()
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
            ->where('user_id', $userId)
            ->groupBy('user_id', 'creative_code')
            ->orderBy('profit', 'DESC')
            ->get();

        return [
            'summary'   => $summary,
            'creatives' => $creatives
        ];
    }

    /**
     * Sub-select padrão (tasks → redtrack)
     */
    private function baseSubQuery(int $roleId)
    {
        return DB::table('redtrack_reports AS rt')

            // 🔗 Match correto
            ->join('tasks AS t', function ($join) {
                $join->on(
                    DB::raw('LOWER(rt.ad_code)'),
                    '=',
                    DB::raw('LOWER(t.code)')
                );
            })

            // 🔗 Quem produziu
            ->join('sub_tasks AS st', 'st.task_id', '=', 't.id')
            ->join('user_tasks AS ut', 'ut.sub_task_id', '=', 'st.id')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')

            // ⚠️ role opcional (recomendo mover para fora)
            ->whereBetween('rt.date', [$this->startAt, $this->finishAt])
            ->where('ur.role_id', $roleId)

            ->select(
                'u.id AS user_id',
                'u.name AS user_name',
                't.code AS creative_code',
                DB::raw('SUM(rt.clicks) AS rt_clicks'),
                DB::raw('SUM(rt.conversions) AS rt_conversions'),
                DB::raw('SUM(rt.cost) AS rt_cost'),
                DB::raw('SUM(rt.profit) AS rt_profit'),
                DB::raw('SUM(rt.cost + rt.profit) AS rt_revenue')
            )

            // 🔥 AGRUPA NO NÍVEL CERTO
            ->groupBy(
                'u.id',
                'u.name',
                't.code'
            );
    }


    /* Conveniências específicas */
    public function rankEditors($limit = null): Collection
    {
        return $this->rank(3, $limit);
    }

    public function rankCopies($limit = null): Collection
    {
        return $this->rank(2, $limit);
    }

    public function editorDetails(int $userId): array
    {
        return $this->talentDetails(3, $userId);
    }

    public function copyDetails(int $userId): array
    {
        return $this->talentDetails(2, $userId);
    }
}
