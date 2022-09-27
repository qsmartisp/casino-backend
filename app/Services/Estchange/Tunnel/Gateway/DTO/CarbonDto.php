<?php

namespace App\Services\Estchange\Tunnel\Gateway\DTO;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class CarbonDto extends DataTransferObject
{
    public Carbon $date;
}
