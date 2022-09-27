<?php

namespace App\Services\Simplepay\DTO\Webhook;

use Spatie\DataTransferObject\DataTransferObject;

class ResponseParamsDTO extends DataTransferObject
{
    public ?int $status;

    public ?int $code;

    public string $message;

    public int $timestamp;

    public ?string $error; // todo: remove?
}
