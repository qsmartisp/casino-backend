<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Sender implements SenderInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected string $apiKey,
    ) {
    }

    /**
     * @param string $apiUrl
     * @param string $method
     * @param array $params
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function send(
        string $apiUrl,
        string $method = self::METHOD_GET,
        array $params = [],
    ): ResponseInterface {
        try {
            return $this->client
                ->request($method, $apiUrl, [
                    RequestOptions::JSON => $method === self::METHOD_POST
                        ? $params
                        : null,
                    RequestOptions::HEADERS => [
                        'X-API-KEY' => $this->apiKey,
                    ],
                ]);
        } catch (BadResponseException $exception) {
            return $exception->getResponse();
        }
    }
}
