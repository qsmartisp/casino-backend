<?php

namespace App\Services\Fundist\Exceptions;

use App\Services\Fundist\Responses\AddUserResponse;
use Throwable;

class CantCreateUserException extends FundistException
{
    protected const MESSAGE = "Can't create user";

    public function __construct(
        protected AddUserResponse $response,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        $message = $message ?: static::MESSAGE;

        parent::__construct($message . ' (' . $response->toString() . ')', $code, $previous);
    }
}
