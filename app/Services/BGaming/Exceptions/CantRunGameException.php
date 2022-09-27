<?php

namespace App\Services\BGaming\Exceptions;

use App\Services\BGaming\Responses\LaunchOptionsResponse;
use Throwable;

class CantRunGameException extends BGamingException
{
    protected const MESSAGE = "Can't run game";

    public function __construct(
        protected LaunchOptionsResponse $response,
        $message = "",
        $code = 0,
        Throwable $previous = null,
    ) {
        parent::__construct($message ? : static::MESSAGE . ' (' . $response->toString() . ')', $code, $previous);
    }
}
