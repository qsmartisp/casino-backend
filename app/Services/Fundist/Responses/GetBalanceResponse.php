<?php

namespace App\Services\Fundist\Responses;

use App\Services\Fundist\Response;

class GetBalanceResponse extends Response
{
    public function isNotFound(): bool
    {
        // todo
        $str = $this->toString();

        [$code] = explode(',', $str);

        return (int)$code === 17; // todo: static
    }
}
