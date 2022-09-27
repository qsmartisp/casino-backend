<?php

namespace App\Services\Local\Repositories;

use App\Enums\Withdrawal\Status;
use App\Models\Withdrawal;
use App\Services\Local\Repository;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @method Withdrawal store(array $options)
 */
class WithdrawalRepository extends Repository
{
    public function query(): Withdrawal|Builder
    {
        return Withdrawal::query();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findById(string $id): Withdrawal
    {
        return $this->query()->findOrFail($id);
    }

    public function getById(string $id): ?Withdrawal
    {
        return $this->query()->find($id);
    }

    public function getByExternalWithdrawalId(string $externalWithdrawId): ?Withdrawal
    {
        return $this->query()
            ->where('external_withdrawal_id', $externalWithdrawId)
            ->first();
    }

    public function init(string $transactionId): Withdrawal
    {
        return $this->store([
            'transaction_id' => $transactionId,
            'status' => Status::Pending,
        ]);
    }

    public function accept(Withdrawal $withdrawal): bool
    {
        return $this->update($withdrawal, [
            'status' => Status::Accepted,
        ]);
    }

    public function sending(Withdrawal $withdrawal, string $externalWithdrawId): bool
    {
        return $this->update($withdrawal, [
            'status' => Status::Sending,
            'external_withdrawal_id' => $externalWithdrawId,
        ]);
    }

    public function finish(Withdrawal $withdrawal, string $txId): bool
    {
        return $this->update($withdrawal, [
            'status' => Status::Finished,
            'tx_id' => $txId,
        ]);
    }

    public function failed(Withdrawal $withdrawal): bool
    {
        return $this->update($withdrawal, [
            'status' => Status::Failed,
        ]);
    }
}
