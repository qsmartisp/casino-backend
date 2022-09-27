<?php

namespace App\Services\Local\Repositories;

use App\Models\WithdrawalFail;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @method WithdrawalFail store(array $options)
 */
class WithdrawalFailRepository extends Repository
{
    public function query(): WithdrawalFail|Builder
    {
        return WithdrawalFail::query();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findById(string $id): WithdrawalFail
    {
        return $this->query()->findOrFail($id);
    }

    public function getById(string $id): ?WithdrawalFail
    {
        return $this->query()->find($id);
    }
}
