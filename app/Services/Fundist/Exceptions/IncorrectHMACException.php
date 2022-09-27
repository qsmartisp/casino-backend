<?php

namespace App\Services\Fundist\Exceptions;

use Throwable;

class IncorrectHMACException extends FundistException
{
    protected const MESSAGE = "Incorrect HMAC";

    public function __construct(
        protected array $extra = [],
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message ?: static::MESSAGE, $code, $previous);
    }

    public function getExtra(): array
    {
        return $this->extra;
    }
}
