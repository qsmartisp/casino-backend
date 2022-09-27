<?php

namespace App\Services\BGaming;

use App\Services\BGaming\DTO\RequestDto;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger,
        protected SignatureService $signatureService,
    ) {
    }

    /**
     * @param RequestDto $dto
     *
     * @return ResponseInterface|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     * @throws \Exception
     */
    public function send(RequestDto $dto): null|ResponseInterface
    {
        try {
            return $this->client->request(
                method: 'post',
                uri: $dto->url,
                options: [
                    RequestOptions::JSON => $dto->params->toArray(),
                    RequestOptions::HEADERS => [
                        'X-REQUEST-SIGN' => $this->signatureService->signature($dto->params->toString()),
                    ],
                ],
            );
        } catch (BadResponseException $exception) {
            abort(
                $exception->getResponse()->getStatusCode(),
                $exception->getResponse()->getBody()->getContents(),
            );

            return null;
        }
    }
}
