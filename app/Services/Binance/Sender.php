<?php

namespace App\Services\Binance;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class Sender implements SenderInterface
{
    public function __construct(
        protected ClientInterface $client,
    ) {
    }

    /**
     * @param string $apiUrl
     * @param string $method
     * @param array $params
     *
     * @return ResponseInterface|null
     *
     * @throws GuzzleException
     * @throws UnknownProperties
     */
    public function send(
        string $apiUrl,
        string $method = self::METHOD_GET,
        array $params = [],
    ): null|ResponseInterface {
        try {
            return $this->client
                ->request($method, $apiUrl, [
                    RequestOptions::HEADERS => [
                        'X-MBX-APIKEY' => 'NONE',
                    ],
                ]);
        } catch (BadResponseException $exception) {
            // todo: remove laravel dependency
            abort(
                $exception->getResponse()->getStatusCode(),
                $exception->getResponse()->getBody()->getContents(),
            );

            return null;
        }
    }
}
