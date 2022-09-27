<?php

namespace App\Services\Game;

use App\Services\Game\Integrations\DTO\GameDto;

class Launch
{
    public function __construct(
        public GameDto $dto,
        public array $response,
    ) {
    }
}
