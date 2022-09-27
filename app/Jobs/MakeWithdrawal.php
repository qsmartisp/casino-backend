<?php

namespace App\Jobs;

use App\Models\Withdrawal;
use App\Services\Estchange\Tunnel\Gateway\EstchangeHelper;
use App\Services\Estchange\Tunnel\Gateway\EstchangeService;
use App\Services\Estchange\Tunnel\Gateway\Exceptions\CantWithdrawal;
use App\Services\Local\Repositories\WithdrawalFailRepository;
use App\Services\Local\Repositories\WithdrawalRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakeWithdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Withdrawal $withdrawal,
    ) {
    }

    /**
     * @throws CantWithdrawal
     */
    public function handle(
        EstchangeService $estchangeService,
        WithdrawalRepository $withdrawalRepository,
        WithdrawalFailRepository $withdrawalFailRepository,
    ): void {
        $estchangeWithdrawal = $estchangeService->withdrawal(
            abs($this->withdrawal->transaction->amountFloat),
            $this->withdrawal->transaction->metaAddress,
            $this->withdrawal->transaction->metaCoin,
            $this->getCurrency(),
        );

        if ($estchangeWithdrawal->isNotSuccess()) {
            $withdrawalRepository->failed($this->withdrawal);
            $withdrawalFailRepository->store([
                'withdrawal_id' => $this->withdrawal->id,
                'response_data' => $estchangeWithdrawal->toArray(),
            ]);

            throw new CantWithdrawal($estchangeWithdrawal);
        }

        $withdrawalRepository->sending($this->withdrawal, $estchangeWithdrawal->getWithdrawalId());
    }

    private function getCurrency(): ?string
    {
        return EstchangeHelper::isCryptoCurrency(
            $this->withdrawal->transaction->wallet->currency
        )
            ? null
            : $this->withdrawal->transaction->wallet->currency;
    }
}
