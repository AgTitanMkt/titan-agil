<?php

namespace App\Providers;

use App\Models\SubTask;
use App\Policies\SubTaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SubTask::class => SubTaskPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}