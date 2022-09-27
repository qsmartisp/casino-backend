<?php

namespace App\Services\Local\Repositories;

use App\Enums\Deposit\Status;
use App\Models\Deposit;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @method Deposit store(array $options)
 */
class DepositRepository extends Repository
{
    public function query(): Deposit|Builder
    {
        return Deposit::query();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findById(string $id): Deposit
    {
        return $this->query()->findOrFail($id);
    }

    public function getById(?int $id): ?Deposit
    {
        return $id ? $this->query()->find($id) : null;
    }

    public function getByExternalTransactionId(string $externalWithdrawId): ?Deposit
    {
        return $this->query()
            ->where('external_transaction_id', $externalWithdrawId)
            ->first();
    }

    public function init(
        string $transactionId,
        string $currency,
        Status $status = Status::Pending,
    ): Deposit {
        return $this->store([
            'transaction_id' => $transactionId,
            'currency' => $currency,
            'status' => $status,
        ]);
    }

    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function finish(Deposit $deposit, string $externalTransactionId): bool
    {
        // todo: deposit service
        return $deposit->transaction->wallet->confirm($deposit->transaction)
            && $this->update($deposit, [
                'external_transaction_id' => $externalTransactionId,
                'status' => Status::Accepted,
            ]);
    }
}
