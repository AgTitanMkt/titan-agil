<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeadlineSubask extends Model
{
    protected $fillable = [
        'subtask_id',
        'deadline_editor',
        'deadline_copy'
    ];
}
