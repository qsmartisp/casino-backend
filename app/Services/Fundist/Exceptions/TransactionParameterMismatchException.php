<?php

namespace App\Services\Fundist\Exceptions;

class TransactionParameterMismatchException extends FundistException
{
    protected const MESSAGE = "Transaction parameter mismatch";
}
