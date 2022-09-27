<?php

namespace App\Services\BGaming\Exceptions;

use RuntimeException;
use Throwable;

abstract class BGamingException extends RuntimeException
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
