<?php

namespace App\Enums\Transaction;

enum Status: string
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
