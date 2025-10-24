<?php

namespace App\Services\DTOs;

class BaseDTO 
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function validatedDTO(): array
    {

        return get_object_vars($this);
    }
}