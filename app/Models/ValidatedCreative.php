<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidatedCreative extends Model
{
     protected $fillable = [
        'ad',
        'ad_code',
        'subtask_id',
        'total_conversions',
        'total_cost',
        'total_profit',
        'roi',
        'validated_at',
        'potential_at',
        'is_potential',
        'is_validated',
    ];
}
