<?php

namespace App\Services\Simplepay\Exceptions;

class WalletNotFound extends SimplepayException
{
    protected const MESSAGE = "Wallet Not Found";
}
