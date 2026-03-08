<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubTask;

class SubTaskPolicy
{
    public function view(User $user, SubTask $subTask): bool
    {
        return
            $subTask->created_by === $user->id
            || $subTask->revised_by === $user->id
            || $subTask->assignments()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function update(User $user, SubTask $subTask): bool
    {
        return $this->view($user, $subTask);
    }
}
