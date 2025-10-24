<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskFiles extends Model
{
    protected $fillable = [
        'task_id',
        'file_type',
        'file_url',
        'uploaded_by',
    ];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
    public function uploader(): HasOne
    {
        return $this->hasOne(User::class,'id','uploaded_by');
    }
}
