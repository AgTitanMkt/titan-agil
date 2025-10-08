<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'executed_by',
        'revised_by',
        'status',
        'due_date',
        'role_id',
    ];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
    public function reviwer(): HasOne
    {
        return$this->hasOne(User::class,'id','revised_by');
    }
    public function executer(): HasOne
    {
        return$this->hasOne(User::class,'id','executed_by');
    }
    public function role(): HasOne
    {
        return $this->hasOne(Role::class);
    }

}
