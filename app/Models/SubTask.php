<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubTask extends Model
{
    public const STATUS = [
        'CREATED' => 'CREATED',
        'APPROVED' => 'APPROVED',
        'REVIEW' => 'REVIEW',
        'REVIEW_COPY' => 'REVIEW_COPY',
        'REVIEW_EDITOR' => 'REVIEW_EDITOR',
        'PENDING_EDITOR' => 'PENDING_EDITOR',
        'PENDING_COPY' => 'PENDING_COPY',
        'CONCLUDED' => 'CONCLUDED',
        'PENDING' => 'PENDING',
        'DONE' => 'DONE',
        'PUBLISHED' => 'PUBLISHED',
        'ASSIGNED' => 'ASSIGNED',
    ];

    protected $fillable = [
        'task_id',
        'description',
        'status',
        'due_date',
        'hook',
        'variation',
        'variation_number',
        'platform_id',
        'revised_by',
        'created_by',
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
        return $this->belongsToMany(User::class, 'user_tasks', 'sub_task_id', 'user_id');
    }

    public function platform(): HasOne
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(UserTask::class, 'sub_task_id', 'id');
    }

    public function revisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revised_by', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(SubtaskFile::class, 'subtask_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(SubtaskHistories::class)->latest();
    }

    public function addHistory($event, $description = null, $old = null, $new = null)
    {
        SubtaskHistories::create([
            'sub_task_id' => $this->id,
            'user_id' => auth()->id(),
            'event' => $event,
            'description' => $description,
            'old_value' => $old,
            'new_value' => $new,
        ]);
    }
}
