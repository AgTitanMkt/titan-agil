<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $startDateFilter = null;
    public $endDateFilter = null;
    public $copywriterFilter = null;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function role($role): bool
    {
        $roles = $this->roles->pluck('title')->toArray();
        return in_array($role, $roles);
    }

    public function userTasks($start)
    {
        return $this->hasMany(UserTask::class);
    }

    public function subTasks()
    {
        return $this->hasManyThrough(
            SubTask::class,
            UserTask::class,
            'user_id',        // user_tasks.user_id
            'id',             // sub_tasks.id
            'id',             // users.id
            'sub_task_id'     // user_tasks.sub_task_id
        );
    }

    public function scopeWithRole($query, $roleId)
    {
        return $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('role_id', $roleId);
        });
    }

    public function tasks()
    {
        /** ------------------------------------------------------------
         * SUBQUERY: primeira ocorrência do criativo no RedTrack
         * ------------------------------------------------------------ */
        $firstDateSub = DB::table('redtrack_reports')
            ->selectRaw('LOWER(ad_code) AS ad_code_norm, MIN(date) AS first_redtrack_date')
            ->groupBy(DB::raw('LOWER(ad_code)'));

        /** ------------------------------------------------------------
         * QUERY PRINCIPAL
         * ------------------------------------------------------------ */
        return DB::table('user_tasks AS ut')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('sub_tasks AS st', 'st.id', '=', 'ut.sub_task_id')
            ->join('tasks AS t', 't.id', '=', 'st.task_id')
            ->join('nichos AS n', 'n.id', '=', 't.nicho')

            /* ============================================================
         * 🔗 LEFT JOIN COM REDTRACK (NÃO elimina tasks)
         * ============================================================ */
            ->leftJoin('redtrack_reports AS rr', function ($join) {
                $join->on(
                    DB::raw('LOWER(rr.ad_code)'),
                    '=',
                    DB::raw('LOWER(t.code)')
                );
            })

            /* ============================================================
         * 🔗 PRIMEIRA DATA NO REDTRACK
         * ============================================================ */
            ->leftJoinSub($firstDateSub, 'fr', function ($join) {
                $join->on(
                    'fr.ad_code_norm',
                    '=',
                    DB::raw('LOWER(t.code)')
                );
            })

            /* ============================================================
         * 🎯 FILTRO DE COPYWRITER (FUNCIONAL)
         * ============================================================ */
            ->when($this->copywriterFilter, function ($q) {
                $q->whereIn('ut.user_id', $this->copywriterFilter);
            }, function ($q) {
                // fallback: usuário atual
                $q->where('ut.user_id', $this->id);
            })

            /* ============================================================
         * 📅 FILTRO DE DATA (PRODUÇÃO)
         * ============================================================ */
            ->when($this->startDateFilter && $this->endDateFilter, function ($q) {
                $q->whereBetween('t.created_at', [
                    $this->startDateFilter,
                    $this->endDateFilter
                ]);
            })

            /* ============================================================
         * 📊 SELECT FINAL (ONLY_FULL_GROUP_BY SAFE)
         * ============================================================ */
            ->selectRaw("
            t.id AS task_id,
            t.code,
            n.id AS nicho_id,
            n.name AS nicho_name,
            u.name AS agent_name,

            MAX(rr.date) AS redtrack_date,
            fr.first_redtrack_date,

            SUM(rr.clicks) AS total_clicks,
            SUM(rr.conversions) AS total_conversions,
            SUM(rr.cost) AS total_cost,
            SUM(rr.profit) AS total_profit,

            CASE 
                WHEN SUM(rr.cost) > 0 THEN SUM(rr.profit) / SUM(rr.cost)
                ELSE 0
            END AS roi,

            CASE 
                WHEN COUNT(rr.id) = 0 THEN 'inconsistente'
                ELSE 'ok'
            END AS status,

            CASE 
                WHEN COUNT(rr.id) = 0 THEN 'não encontrado no redtrack'
                ELSE NULL
            END AS info,

            COUNT(DISTINCT t.id) AS produzido,
            COUNT(DISTINCT rr.id) AS testados,

            CASE 
                WHEN SUM(rr.conversions) >= 20
                 AND (SUM(rr.profit) / NULLIF(SUM(rr.cost), 0)) >= 1.8
                THEN 1 ELSE 0
            END AS validados,

            CASE 
                WHEN SUM(rr.conversions) >= 20
                 AND (SUM(rr.profit) / NULLIF(SUM(rr.cost), 0)) >= 1.8
                THEN 100 ELSE 0
            END AS win_rate,

            CASE 
                WHEN SUM(rr.conversions) >= 1
                 AND (SUM(rr.profit) / NULLIF(SUM(rr.cost), 0)) >= 1
                THEN 1 ELSE 0
            END AS em_potencial,

            CASE 
                WHEN SUM(rr.clicks) > 0 
                THEN SUM(rr.cost) / SUM(rr.clicks)
                ELSE NULL
            END AS cpc,

            CASE 
                WHEN SUM(rr.clicks) > 0
                THEN (SUM(rr.profit) + SUM(rr.cost)) / SUM(rr.clicks)
                ELSE NULL
            END AS epc
        ")

            ->groupBy(
                't.id',
                't.code',
                'u.name',
                'fr.first_redtrack_date',
                'n.id',
                'n.name'
            )

            ->orderBy('total_profit', 'DESC');
    }


    // Permite $user->redtrackTasks; funcionar como atributo
    public function getTasksAttribute()
    {
        return $this->tasks()->get();
    }

    public function applyFilter($start, $end, $copywriterFilter = null)
    {
        $this->startDateFilter = $start;
        $this->endDateFilter = $end;

        // sempre converte filtro para array (ou null)
        if ($copywriterFilter) {
            $this->copywriterFilter = is_array($copywriterFilter)
                ? $copywriterFilter
                : [$copywriterFilter];
        } else {
            $this->copywriterFilter = null; // IMPORTANTE!
        }

        return $this;
    }
}
