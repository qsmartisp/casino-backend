<?php

namespace App\Services\Coinbase\Enums;

enum ChargeStatus: string
{
    case NEW = 'NEW';
    case PENDING = 'PENDING';
    case COMPLETED = 'COMPLETED';
    case EXPIRED = 'EXPIRED';
    case UNRESOLVED = 'UNRESOLVED';
    case RESOLVED = 'RESOLVED';
    case CANCELED = 'CANCELED';
    case REFUND_PENDING = 'REFUND PENDING';
    case REFUNDED = 'REFUNDED';
}
