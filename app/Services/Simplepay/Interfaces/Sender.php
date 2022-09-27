<?php

namespace App\Services\Simplepay\Interfaces;

use App\Services\Simplepay\DTO\RequestDto;
use Psr\Http\Message\ResponseInterface;

interface Sender
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(RequestDto $dto): null|ResponseInterface;
}
