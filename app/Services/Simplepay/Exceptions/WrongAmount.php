<?php

namespace App\Services\Simplepay\Exceptions;

class WrongAmount extends SimplepayException
{
    protected const MESSAGE = "Wrong Amount";
}
