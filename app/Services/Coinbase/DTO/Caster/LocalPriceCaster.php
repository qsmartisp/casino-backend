<?php

namespace App\Services\Coinbase\DTO\Caster;

use App\Services\Coinbase\DTO\LocalPriceDto;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LocalPriceCaster implements Caster
{
    /**
     * @param array|mixed $value
     *
     * @return mixed
     *
     * @throws UnknownProperties
     */
    public function cast(mixed $value): LocalPriceDto
    {
        return new LocalPriceDto(
            amount: $value['params']['amount'] ?? "",
            currency: $value['params']['currency'] ?? "",
        );
    }
}
