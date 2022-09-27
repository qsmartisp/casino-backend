<?php

namespace App\Services\Fundist;

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

    public function isOk(): bool
    {
        return $this->isJson() // todo
            ? $this->getBaseResponse()->getStatusCode() === 200
            : $this->getFundistCode() === 1;
    }

    public function isFailed(): bool
    {
        return $this->isOk() === false;
    }

    public function getFundistCode(): int
    {
        $str = $this->toString();

        [$code] = explode(',', $str);

        return (int)$code;
    }

    public function getHtml(): string
    {
        $str = $this->toString();

        [, $html] = explode(',', $str, 2);

        return $html;
    }
}
