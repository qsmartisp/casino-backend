<?php

namespace App\Services\Simplepay;

use App\Services\Simplepay\DTO\Webhook\ResponseParamsDTO;
use App\Services\Simplepay\Enums\Webhook\Status;
use App\Services\Simplepay\Exceptions\SimplepayException;
use App\Services\Simplepay\Responses\Webhook\CheckResponse;
use App\Services\Simplepay\Responses\Webhook\ErrorResponse;
use Carbon\Carbon;

class WebhookService
{
    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleCheck(
        Carbon $now,
    ): WebhookResponse {
        return new CheckResponse(
            new ResponseParamsDTO(
                status: Status::Success->value,
                message: "Account exist",
                timestamp: $now->timestamp,
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handlePay(
        Carbon $now,
        callable $callbackOnSuccess,
    ): WebhookResponse {
        $callbackOnSuccess();

        return new CheckResponse(
            new ResponseParamsDTO(
                status: Status::SuccessPay->value,
                message: "Payment success",
                timestamp: $now->timestamp,
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handlePayment(
        Carbon $now,
    ): WebhookResponse {
        // TODO

        return new CheckResponse(
            new ResponseParamsDTO(
                status: Status::Success->value,
                message: "OK",
                timestamp: $now->timestamp,
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleTransfer(
        Carbon $now,
    ): WebhookResponse {
        // TODO

        return new CheckResponse(
            new ResponseParamsDTO(
                status: Status::Success->value,
                message: "OK",
                timestamp: $now->timestamp,
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleError(
        Carbon $now,
        SimplepayException $exception,
        ?Status $status = null,
        ?string $message = null,
    ): WebhookResponse {
        // TODO

        return new ErrorResponse(
            new ResponseParamsDTO(
                status: $status->value ?? Status::Fail->value,
                message: $exception->getMessage() ?? $message ?? 'Error',
                timestamp: $now->timestamp,
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleException(
        Carbon $now,
        \Throwable $e,
    ): WebhookResponse {
        // TODO

        return new ErrorResponse(
            new ResponseParamsDTO(
                status: Status::Error->value,
                message: $e->getMessage() ?? '500 Error',
                timestamp: $now->timestamp,
            )
        );
    }
}
