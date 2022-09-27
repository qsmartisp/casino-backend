<?php

namespace App\Services\BGaming\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class UrlsDto extends DataTransferObject
{
    public string $return_url;

    public string $deposit_url;
}
