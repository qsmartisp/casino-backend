<?php

namespace App\Services\Estchange\Tunnel\Gateway\Exceptions;

use App\Services\Estchange\Tunnel\Gateway\Responses\WithdrawalResponse;
use Throwable;

class CantWithdrawal extends EstchangeException
{
    protected const MESSAGE = "Can't make withdrawal";

    public function __construct(
        protected WithdrawalResponse $response,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message . ' (' . $response->toString() . ')', $code, $previous);
    }
}
