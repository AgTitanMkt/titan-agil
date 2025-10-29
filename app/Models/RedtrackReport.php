<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedtrackReport extends Model
{
    protected $fillable = [
        'name', 'source', 'alias' , 'date', 'clicks', 'conversions', 'cost', 'profit', 'roi','normalized_rt_ad'
    ];
}
