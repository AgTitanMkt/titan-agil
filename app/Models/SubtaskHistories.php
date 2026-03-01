<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtaskHistories extends Model
{
    protected $fillable = [
        'sub_task_id',
        'user_id',
        'event',
        'description',
        'old_value',
        'new_value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}