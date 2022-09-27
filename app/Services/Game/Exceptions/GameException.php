<?php

namespace App\Services\Game\Exceptions;

abstract class GameException extends \RuntimeException
{
    protected const MESSAGE = "Something went wrong";

    public function __construct(
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message ? : static::MESSAGE, $code, $previous);
    }
}