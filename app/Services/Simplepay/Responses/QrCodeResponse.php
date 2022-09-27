<?php

namespace App\Services\Simplepay\Responses;

use App\Services\Simplepay\Response;
use Illuminate\Support\Collection;

class QrCodeResponse extends Response
{
    public function fee(): string
    {
        return $this->getDecodedResponse()['response']['fee'];
    }

    public function amount(): string
    {
        return $this->getDecodedResponse()['response']['amount'];
    }

    public function currency(): string
    {
        return $this->getDecodedResponse()['response']['currency'];
    }

    public function order(): string
    {
        return $this->getDecodedResponse()['response']['order'];
    }

    public function id(): string
    {
        return $this->getDecodedResponse()['response']['id'];
    }

    public function raw(): string
    {
        return $this->attributes()->first(fn($item) => $item['key'] === 'qrcode')['value'];
    }

    public function base64(): string
    {
        return $this->attributes()->first(fn($item) => $item['key'] === 'showData')['value'];
    }

    protected function attributes(): Collection
    {
        return collect($this->getDecodedResponse()['response']['attributes']);
    }
}
