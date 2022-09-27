<?php

namespace App\Services\Simplepay\DTO\Webhook;

use Spatie\DataTransferObject\DataTransferObject;

class DataDto extends DataTransferObject
{
    public string $currency;
}
