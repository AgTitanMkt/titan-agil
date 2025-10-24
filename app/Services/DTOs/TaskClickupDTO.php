<?php

namespace App\Services\DTOs;

use App\Models\Role;
use App\Models\User;
use DateTime;

class TaskClickupDTO extends BaseDTO
{
    public function __construct(
        public int $created_by,
        public string $title,
        public string $code,
        public string $description,
        public int $executed_by,
        public int $revised_by,
        public string $status,
        public DateTime $due_date,
        public int $role_id
    ) {}

}