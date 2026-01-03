<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'nicho',
        'code',
        'normalized_code'
    ];

    public function subTasks(): HasMany
    {
        return $this->hasMany(SubTask::class);
    }

    public function redtrackReports()
    {
        return $this->hasMany(RedtrackReport::class, 'ad_code', 'code');
    }
}
