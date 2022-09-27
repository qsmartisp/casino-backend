<?php

namespace App\Services\Fundist;

use Psr\Http\Message\ResponseInterface;

interface SenderInterface
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $apiUrl, array $params = []): null|ResponseInterface;
}
