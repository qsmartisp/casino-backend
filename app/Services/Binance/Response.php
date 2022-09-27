<?php

namespace App\Services\Binance;

use JsonException;
use Psr\Http\Message\ResponseInterface;

abstract class Response
{
    private static string $content;

    private array $decodedResponse;

    /**
     * @throws JsonException
     */
    public function __construct(
        protected ResponseInterface $baseResponse
    ) {
        static::$content = $baseResponse->getBody()->getContents();

        $this->decodedResponse = $this->decodeResponse();
    }

    /**
     * @throws JsonException
     */
    private function decodeResponse(): array
    {
        if ($this->isJson()) {
            return json_decode(
                static::$content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        return [];
    }

    protected function getBaseResponse(): ResponseInterface
    {
        return $this->baseResponse;
    }

    protected function getDecodedResponse(): array
    {
        return $this->decodedResponse;
    }

    private function isJson(): bool
    {
        return str_contains(
            $this->getBaseResponse()->getHeaderLine('Content-Type'),
            'application/json',
        );
    }

    public function toArray(): array
    {
        return $this->getDecodedResponse();
    }

    public function toString(): string
    {
        return static::$content;
    }
}
