<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'created_by',
        'created_by',
        'title',
        'code',
    ];

    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class);
    }
}
