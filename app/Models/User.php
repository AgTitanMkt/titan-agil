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

    public function userTasks()
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
        return DB::table('user_tasks AS ut')
            ->join('users AS u', 'u.id', '=', 'ut.user_id')
            ->join('sub_tasks AS st', 'st.id', '=', 'ut.sub_task_id')
            ->join('tasks AS t', 't.id', '=', 'st.task_id')
            ->leftJoin('redtrack_reports AS rr', 'rr.normalized_rt_ad', '=', 't.normalized_code')
            ->where('ut.user_id', $this->id)

            ->when($this->startDateFilter && $this->endDateFilter, function ($q) {
                $q->whereBetween('rr.date', [$this->startDateFilter, $this->endDateFilter]);
            })
            ->when($this->copywriterFilter, function ($q) {
                $q->whereIn('u.name',[$this->copywriterFilter]);
            })
            ->selectRaw("
            ut.sub_task_id,
            st.task_id,
            t.code,
            t.normalized_code,
            u.name AS agent_name,
            MAX(rr.date) AS redtrack_date,
            SUM(rr.clicks) AS total_clicks,
            SUM(rr.conversions) AS total_conversions,
            SUM(rr.cost) AS total_cost,
            SUM(rr.profit) AS total_profit,
            CASE 
                WHEN SUM(rr.cost) > 0 THEN SUM(rr.profit) / SUM(rr.cost)
                ELSE 0
            END AS roi,
            CASE 
                WHEN MAX(rr.id) IS NULL THEN 'inconsistente'
                ELSE 'ok'
            END AS status,
            CASE 
                WHEN MAX(rr.id) IS NULL THEN 'não encontrado no redtrack'
                ELSE NULL
            END AS info
        ")
            ->groupBy(
                'ut.sub_task_id',
                'st.task_id',
                't.code',
                't.normalized_code',
                'u.name'
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
        if($copywriterFilter){
            $this->copywriterFilter = $copywriterFilter;
        }

        return $this;
    }
}
