<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    // 🔹 Usuário que criou as tarefas (criador)
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    // 🔹 Usuário que executa as subtarefas
    public function executedSubTasks(): HasMany
    {
        return $this->hasMany(SubTask::class, 'executed_by');
    }

    // 🔹 Usuário que revisa as subtarefas
    public function revisedSubTasks(): HasMany
    {
        return $this->hasMany(SubTask::class, 'revised_by');
    }
    public function allRelatedTasks()
    {
        $tasks = $this->createdTasks()->get();

        $subTasks = SubTask::where(function ($query) {
            $query->where('executed_by', $this->id)
                ->orWhere('revised_by', $this->id);
        })->get();

        return $tasks->merge($subTasks);
    }
}
