<?php

namespace App\Enums\Simplepay\Webhook;

enum Method: string
{
    case Check = 'check';
    case Pay = 'pay';

    case PaymentReversed = 'payment.reversed';
    case PaymentRefunded = 'payment.refunded';
    case PaymentRejected = 'payment.rejected';
    case PaymentCharged = 'payment.charged';
    case PaymentVoided = 'payment.voided';

    case TransferPaid = 'transfer.paid';
    case TransferFailed = 'transfer.failed';
    case TransferRejected = 'transfer.rejected';
    case TransferReversed = 'transfer.reversed';
}
