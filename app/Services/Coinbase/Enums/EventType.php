<?php

namespace App\Services\Coinbase\Enums;

enum EventType: string
{
    case ChargeCreated = 'charge:created';
    case ChargeConfirmed = 'charge:confirmed';
    case ChargeFailed = 'charge:failed';
    case ChargeDelayed = 'charge:delayed';
    case ChargePending = 'charge:pending';
    case ChargeResolved = 'charge:resolved';

    case UNKNOWN = 'UNKNOWN';

    public function label(): string
    {
        return match($this) {
            self::ChargeCreated => mb_strtolower(ChargeStatus::NEW->value),
            self::ChargeConfirmed => mb_strtolower(ChargeStatus::COMPLETED->value),
            self::ChargeFailed, self::ChargeDelayed => mb_strtolower(ChargeStatus::UNRESOLVED->value),
            self::ChargePending => mb_strtolower(ChargeStatus::PENDING->value),
            self::ChargeResolved => mb_strtolower(ChargeStatus::RESOLVED->value),
            self::UNKNOWN => 'unknown',
        };
    }
}
