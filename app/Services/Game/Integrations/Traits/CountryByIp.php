<?php

namespace App\Services\Game\Integrations\Traits;

trait CountryByIp
{
    protected function getCurrentCountryCode(string $ip): ?string
    {
        try {
            return $this->reader->country($ip)->country->isoCode;
        } catch (\Exception) {
            // todo: fix magic
            return null;
        }
    }
}
