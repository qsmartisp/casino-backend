<?php

namespace App\Services\Fundist\Responses;

use App\Services\Fundist\Response;

class GameFullListResponse extends Response
{
    public function games(): array
    {
        $allData = $this->toArray();

        return $allData['games'] ?? [];
    }

    public function merchants(): array
    {
        $allData = $this->toArray();

        return $allData['merchants'] ?? [];
    }

    public function countriesRestrictions(): array
    {
        $allData = $this->toArray();

        return $allData['countriesRestrictions'] ?? [];
    }
}
