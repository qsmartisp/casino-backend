<?php

namespace App\Services\Simplepay\Exceptions;

class SignatureNotValid extends SimplepayException
{
    protected const MESSAGE = "Signature Not Valid";
}
