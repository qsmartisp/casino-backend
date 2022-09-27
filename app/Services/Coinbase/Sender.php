<?php

namespace App\Services\Coinbase;

use App\Services\Coinbase\DTO\RequestParamsDTO;
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
        protected string $apiKey,
        protected string $apiVersion,
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
        $dto = new RequestParamsDTO(
            params: $params,
            apiUrl: $apiUrl,
            local_price: ['params' => $params],
        );

        // todo: if method=get, then use paginate

        try {
            return $this->client
                ->request($method, $dto->apiUrl, [
                    RequestOptions::JSON => $dto->toArray(),
                    RequestOptions::HEADERS => [
                        'X-CC-Version' => $this->apiVersion,
                        'X-CC-Api-Key' => $this->apiKey,
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
