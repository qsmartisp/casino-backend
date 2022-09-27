<?php

namespace App\Services\Coinbase\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class LocalPriceDto extends DataTransferObject
{
    public ?string $amount;

    public ?string $currency;
}
