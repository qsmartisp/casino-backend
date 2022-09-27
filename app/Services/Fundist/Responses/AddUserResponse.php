<?php

namespace App\Services\Fundist\Responses;

use App\Services\Fundist\Response;

class AddUserResponse extends Response
{
    public function isCreated(): bool
    {
        return (int)$this->toString() === 1; // todo: static
    }
}
