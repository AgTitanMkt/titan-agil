<?php

namespace App\Services\Tasks;

use App\Models\Nicho;
use App\Models\RedtrackReport;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TasksService
{
    private Carbon $startAt;
    private Carbon $endAt;

    public function __construct(?Carbon $startAt = null, ?Carbon $endAt = null)
    {
        if ($startAt) {
            $this->startAt = $startAt;
        } else {
            $this->startAt = Carbon::now()->startOfMonth();
        }

        if ($endAt) {
            $this->endAt = $endAt;
        } else {
            $this->endAt = Carbon::now()->endOfMonth();
        }
    }
    public function insertNichoOnTasks()
    {
        $tasks = Task::where('nicho', null)->get();
        foreach ($tasks as $task) {
            $nicho = Nicho::where('sigla', substr($task->code, 0, 2))->first();
            if (!$nicho)
                continue;
            $task->update(['nicho' => $nicho->id]);
        }
    }

    public function dataNichos()
    {
        return RedtrackReport::from('redtrack_reports as rr')
            ->select(
                'n.name as nicho',
                DB::raw('SUM(rr.clicks) as total_clicks'),
                DB::raw('SUM(rr.conversions) as total_conversions'),
                DB::raw('SUM(rr.cost) as total_cost'),
                DB::raw('SUM(rr.profit) as total_profit'),
                DB::raw('(SUM(rr.profit)/NULLIF(SUM(rr.cost),0)) as roi')
            )
            ->join('tasks as t', function ($join) {
                $join->on(
                    DB::raw('LOWER(t.code)'),
                    '=',
                    DB::raw('LOWER(rr.ad_code)')
                );
            })
            ->join('nichos as n', 'n.id', '=', 't.nicho')
            ->whereBetween('rr.date', [$this->startAt, $this->endAt])
            ->groupBy('n.name')
            ->orderByDesc('total_profit')
            ->get()
            ->map(function ($item) {
                $item->sigla = \App\Models\Nicho::SIGLAS_OFICIAIS[$item->nicho] ?? null;
                return $item;
            });
    }
}
