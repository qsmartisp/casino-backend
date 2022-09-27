<?php

namespace App\Services\Simplepay\DTO\Caster;

use App\Services\Simplepay\DTO\Webhook\DataDto;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class DataCaster implements Caster
{
    /**
     * @param array|mixed $value
     *
     * @return mixed
     *
     * @throws UnknownProperties
     * @throws \JsonException
     */
    public function cast(mixed $value): DataDto
    {
        $data = json_decode($value, false, 512, JSON_THROW_ON_ERROR);

        return new DataDto(
            currency: $data->currency,
        );
    }
}
