<?php

namespace App\Listeners;

use App\Enums\Transaction\System;
use App\Events\BetEvent;
use App\Services\WalletExchangeService;

class BetListener
{
    public function __construct(
        protected WalletExchangeService $walletExchangeService,
    ) {
    }

    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function handle(BetEvent $event): void
    {
        switch ($event->system) {
            case System::BGaming:
                $event->wallet->deposit(amount: $event->amount, meta: [
                    // todo
                ]);
                break;
            case System::Fundist:
                $event->wallet->depositFloat(amount: $event->amount, meta: [
                    // todo
                ]);
                break;
        }
    }
}
