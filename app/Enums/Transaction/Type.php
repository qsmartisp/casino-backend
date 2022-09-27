<?php

namespace App\Enums\Transaction;

enum Type: string
{
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
