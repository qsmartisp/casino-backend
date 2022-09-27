<?php

namespace App\Events;

use App\Enums\Transaction\System;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WinEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public System $system,
        public Wallet $wallet,
        public float $amount,
        public ?string $currency = null,
    ) {
    }
}
