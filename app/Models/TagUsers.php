<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagUsers extends Model
{
    protected $fillable = [
        'user_id',
        'tag',
    ];
}
