<?php

namespace App\Services\Game\Exceptions;

class UnknownAggregator extends \RuntimeException
{
    protected const MESSAGE = "Unknown Aggregator";

    public function __construct(
        ?string $aggregator,
        string $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message ? : static::MESSAGE . " ($aggregator)", $code, $previous);
    }
}
