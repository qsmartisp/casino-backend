<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO\Caster;

use App\Services\Estchange\Tunnel\Gateway\DTO\CarbonDto;
use Carbon\Carbon;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CarbonCaster implements Caster
{
    /**
     * @param array|mixed $value
     *
     * @return mixed
     *
     * @throws UnknownProperties
     */
    public function cast(mixed $value): CarbonDto
    {
        return new CarbonDto(
            date: new Carbon($value),
        );
    }
}
