<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubTask extends Model
{
    public const STATUS = [
        'CREATED' => 'CREATED',
        'APPROVED' => 'APPROVED',
        'REVISED' => 'REVISED',
        'CONCLUDED' => 'CONCLUDED',
        'PENDING' => 'PENDING',
    ];

    protected $fillable = [
        'task_id',
        'description',
        'status',
        'due_date',
        'hook'
    ];

    /**
     * Sempre carregar a task associada
     */
    protected $with = ['task:id,code'];

   /**
     * Relação: SubTask pertence a uma Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    /**
     * Atributo dinâmico: code
     */
    public function getCodeAttribute(): ?string
    {
        return $this->task->code ?? null;
    }

    public function agentes(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_tasks','sub_task_id','user_id');
    }
}
