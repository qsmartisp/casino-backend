<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \GeoIp2\Model\Country
 */
class IpResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'iso_code' => $this->country->isoCode ?? 'Unknown',
            'name' => $this->country->name ?? 'Unknown',
        ];
    }
}
