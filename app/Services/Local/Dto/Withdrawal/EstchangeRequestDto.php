<?php

namespace App\Services\Local\Dto\Withdrawal;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class EstchangeRequestDto extends DataTransferObject
{
    #[MapFrom('request.amount')]
    public float $amount;

    #[MapFrom('request.address')]
    public string $address;

    #[MapFrom('request.coin')]
    public string $coin;

    #[MapFrom('request.currency')]
    public string $currency;
}
