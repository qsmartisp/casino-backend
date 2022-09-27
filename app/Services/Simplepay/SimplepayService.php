<?php

namespace App\Services\Simplepay;

use App\Services\Simplepay\DTO\RequestDto;
use App\Services\Simplepay\Interfaces\Sender as SenderInterface;
use App\Services\Simplepay\Responses\QrCodeResponse;

class SimplepayService
{
    public function __construct(
        protected SenderInterface $sender,
        protected string $qrCodeServiceId,
        private string $apiKey,
    ) {
    }

    protected function sender(): Sender
    {
        return $this->sender;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function makeQrCode(
        string $amount,
        string $order,
        int $timestamp,
        array $meta = [],
    ): QrCodeResponse {
        return new QrCodeResponse(
            $this->sender()->send(
                new RequestDto(
                    method: 'post',
                    url: 'initPayment',
                    params: [
                        'key' => $this->apiKey,
                        'service_id' => $this->qrCodeServiceId,
                        'timestamp' => $timestamp,
                        'amount' => $amount,
                        'order' => $order,
                        '_merchantData' => $meta,
                    ],
                )
            )
        );
    }
}
