<?php

namespace App\Listeners;

use App\Events\WinEvent;
use App\Services\WalletExchangeService;

class WinListener
{
    public function __construct(
        protected WalletExchangeService $walletExchangeService,
    ) {
    }

    public function handle(WinEvent $event): void
    {
        // todo
    }
}
