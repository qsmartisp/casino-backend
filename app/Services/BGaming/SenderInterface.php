<?php

namespace App\Services\BGaming;

use App\Services\BGaming\DTO\RequestDto;
use Psr\Http\Message\ResponseInterface;

interface SenderInterface
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(RequestDto $dto): null|ResponseInterface;
}
