<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    protected $fillable = [
        'user_id',
        'sub_task_id',
        'status',
        'started_at',
        'completed_at',
    ];

    public const STATUS = [
        'ASSIGNED' => 'ASSIGNED',
        'IN_PROGRESS' => 'IN_PROGRESS',
        'DONE' => 'DONE',
        'REJECTED' => 'REJECTED',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subTask()
    {
        return $this->belongsTo(SubTask::class);
    }

}
