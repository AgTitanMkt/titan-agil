<?php

namespace App\Services\Tasks;

use App\Models\Nicho;
use App\Models\Task;

class TasksService
{
    public function insertNichoOnTasks()
    {
        $tasks = Task::where('nicho', null)->get();
        foreach ($tasks as $task) {
            $nicho = Nicho::where('sigla', substr($task->code, 0, 2))->first();
            if (!$nicho)
                continue;
            $task->update(['nicho'=>$nicho->id]);
        }
    }
}
