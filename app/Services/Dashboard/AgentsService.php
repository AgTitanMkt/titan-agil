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

    public function duoMetrics()
    {
        return DB::table('redtrack_reports as rr')
            ->selectRaw("
        CONCAT(tc.tag, ' e ', te.tag) as dupla,

        uc.id as copy_id,
        uc.name as copy_name,
        tc.tag as copy_tag,

        ue.id as editor_id,
        ue.name as editor_name,
        te.tag as editor_tag,
        COUNT(rr.id) as total_creatives,
        SUM(rr.clicks) as total_clicks,
        SUM(rr.conversions) as total_conversions,
        SUM(rr.cost) as total_cost,
        SUM(rr.profit) as total_profit,
        (SUM(rr.profit)/NULLIF(SUM(rr.cost),0)) as roi
    ")
            // COPY (obrigatório)
            ->join('tag_users as tc', 'tc.tag', '=', DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(rr.name, '-', 2), '-', -1)"))
            ->join('users as uc', 'uc.id', '=', 'tc.user_id')

            // EDITOR (obrigatório)
            ->join('tag_users as te', 'te.tag', '=', DB::raw("SUBSTRING_INDEX(rr.name, '-', -1)"))
            ->join('users as ue', 'ue.id', '=', 'te.user_id')

            ->whereBetween('rr.date', [$this->startAt, $this->finishAt])
            ->groupBy('tc.tag', 'te.tag', 'uc.id', 'ue.id')
            ->orderByDesc('total_profit')
            ->get();
    }
}
