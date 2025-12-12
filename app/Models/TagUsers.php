<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TagUsers extends Model
{
    protected $fillable = [
        'user_id',
        'tag',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
