<?php

namespace App\Enums\Game\Aggregator;

enum Name: string
{
    case Fundist = 'Fundist';
    case BGaming = 'BGaming';

    public function slug(): string
    {
        return mb_strtolower($this->value);
    }
}
