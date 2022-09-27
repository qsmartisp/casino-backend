<?php

namespace App\Services\Estchange\Tunnel\Gateway\Exceptions;

use RuntimeException;
use Throwable;

abstract class EstchangeException extends RuntimeException
{
    protected const MESSAGE = "Something went wrong";

    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message ?: static::MESSAGE, $code, $previous);
    }
}