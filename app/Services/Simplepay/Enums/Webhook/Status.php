<?php

namespace App\Services\Simplepay\Enums\Webhook;

/**
 * @see https://api.simplepay.asia/merchant/doc/#api-Callback_API-check
 */
enum Status: int
{
    case Success = 270;
    case SuccessPay = 205;
    case Error = 500;
    case Fail = 475;
    case FailHash = 401;
}
