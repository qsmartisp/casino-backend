<?php

namespace App\Services\Simplepay\Exceptions;

class DepositNotFound extends SimplepayException
{
    protected const MESSAGE = "Deposit Not Found";
}
