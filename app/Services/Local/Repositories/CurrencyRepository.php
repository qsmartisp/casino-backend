<?php

namespace App\Services\Local\Repositories;

use App\Models\Currency;
use App\Models\User;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyRepository extends Repository
{
    public function query(): Currency|Builder
    {
        return Currency::query();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findById(string $id): Currency
    {
        return $this->query()->findOrFail($id);
    }

    public function getById(string $id): ?Currency
    {
        return $this->query()->find($id);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findByCode(string $code): Currency
    {
        return $this->query()->where('code', $code)->with('estchangeRates')->firstOrFail();
    }

    public function setDefault(User $user, Currency $currency): void
    {
        $user->default_currency_id = $currency->id;
        $user->save();
    }
}
