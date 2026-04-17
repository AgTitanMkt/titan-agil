<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedtrackReport extends Model
{
    protected $fillable = [
        'name',
        'source',
        'rt_campaign',
        'alias',
        'date',
        'clicks',
        'conversions',
        'cost',
        'revenue',
        'profit',
        'roi',
        'normalized_rt_ad',
        'ad_code'
    ];
}
