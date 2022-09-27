<?php

namespace App\Services\BGaming\Exceptions;

class CurrencyNotFoundException extends BGamingException
{
    protected const MESSAGE = "Currency Not Found";
}
