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
        return DB::table('tasks AS t')

            // 🔗 ATRIBUIÇÃO (quem produziu)
            ->join('sub_tasks AS st', 'st.task_id', '=', 't.id')
            ->join('user_tasks AS ut', 'ut.sub_task_id', '=', 'st.id')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('user_roles AS ur', function ($join) use ($roleId) {
                $join->on('ur.user_id', '=', 'u.id')
                    ->where('ur.role_id', '=', $roleId);
            })

            // 🔗 RESULTADO FINANCEIRO (criativo inteiro)
            ->join('redtrack_reports AS rt', function ($join) {
                $join->on(
                    DB::raw('LOWER(rt.ad_code)'),
                    '=',
                    DB::raw('LOWER(t.code)')
                );
            })

            ->whereBetween('rt.date', [$this->startAt, $this->finishAt])

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

            // 🔥 AGREGAÇÃO NO NÍVEL CORRETO
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

    public function duoMetrics(): Collection
    {
        $rows = DB::table('tasks as t')

            // 🔹 lucro do criativo (nível correto)
            ->join('redtrack_reports as rr', function ($join) {
                $join->on(
                    DB::raw('LOWER(rr.ad_code)'),
                    '=',
                    DB::raw('LOWER(t.code)')
                );
            })

            // 🔹 sub_tasks do criativo
            ->join('sub_tasks as st', 'st.task_id', '=', 't.id')

            // 🔹 COPY da sub_task
            ->join('user_tasks as utc', 'utc.sub_task_id', '=', 'st.id')
            ->join('users as uc', 'uc.id', '=', 'utc.user_id')
            ->join('user_roles as urc', function ($j) {
                $j->on('urc.user_id', '=', 'uc.id')
                    ->where('urc.role_id', 2);
            })

            // 🔹 EDITOR da sub_task
            ->join('user_tasks as ute', 'ute.sub_task_id', '=', 'st.id')
            ->join('users as ue', 'ue.id', '=', 'ute.user_id')
            ->join('user_roles as ure', function ($j) {
                $j->on('ure.user_id', '=', 'ue.id')
                    ->where('ure.role_id', 3);
            })

            ->whereBetween('rr.date', [$this->startAt, $this->finishAt])

            ->selectRaw("
            uc.id   as copy_id,
            uc.name as copy_name,

            ue.id   as editor_id,
            ue.name as editor_name,

            CONCAT(
                SUBSTRING_INDEX(uc.name, ' ', 1),
                ' e ',
                SUBSTRING_INDEX(ue.name, ' ', 1)
            ) as dupla,

            COUNT(DISTINCT t.code) as total_creatives,

            SUM(rr.profit) as total_profit,
            SUM(rr.cost)   as total_cost,

            CASE 
                WHEN SUM(rr.cost) > 0
                THEN SUM(rr.profit) / SUM(rr.cost)
                ELSE 0
            END as roi
        ")

            ->groupBy(
                'uc.id',
                'uc.name',
                'ue.id',
                'ue.name'
            )

            ->orderByDesc('total_profit')
            ->get();

        return $rows;
    }
}
