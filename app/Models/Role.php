<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ROLES = [
        'ADMIN' => 'ADMIN',
        'COPYWRITER' => 'COPYWRITER',
        'EDITOR' => 'EDITOR',
    ];

    protected $fillable = [
        'title',
        'description'
    ];
}
