<?php

namespace App\Services\BGaming\DTO\Caster;

use App\Services\BGaming\DTO\UrlsDto;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UrlsCaster implements Caster
{
    /**
     * @param array|mixed $value
     *
     * @return mixed
     *
     * @throws UnknownProperties
     */
    public function cast(mixed $value): UrlsDto
    {
        return new UrlsDto(
            return_url: $value['returnUrl'] ?? '',
            deposit_url: $value['depositUrl'] ?? '',
        );
    }
}
