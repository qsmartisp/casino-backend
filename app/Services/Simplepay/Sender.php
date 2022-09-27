<?php

namespace App\Services\Simplepay;

use App\Services\Simplepay\DTO\RequestDto;
use App\Services\Simplepay\Interfaces\Sender as SenderInterface;
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
     * @throws \Exception
     */
    public function send(RequestDto $dto): null|ResponseInterface
    {
        try {
            return $this->client->request(
                method: $dto->method,
                uri: $dto->url,
                options: [
                    RequestOptions::FORM_PARAMS => $dto->params->clone(
                        hash: $this->signatureService->signatureForApi(
                            $this->signatureService->prepareData($dto->params->toArray(), $dto->url)
                        ),
                    )->toArray(),
                ],
            );
        } catch (BadResponseException $exception) {
            // todo: add callbacks and (re-)move it
            abort(
                $exception->getResponse()->getStatusCode(),
                $exception->getResponse()->getBody()->getContents(),
            );

            return null;
        }
    }
}
