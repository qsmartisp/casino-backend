<?php

namespace App\Services\Estchange\Tunnel\Gateway\Exceptions;

use App\Services\Estchange\Tunnel\Gateway\Responses\WalletAddressResponse;
use Throwable;

class CantGetAddressException extends EstchangeException
{
    protected const MESSAGE = "Can't get address";

    public function __construct(
        protected WalletAddressResponse $response,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message . ' (' . $response->toString() . ')', $code, $previous);
    }
}
