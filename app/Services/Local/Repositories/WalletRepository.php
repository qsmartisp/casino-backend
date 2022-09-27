<?php

namespace App\Services\Local\Repositories;

use App\Models\Currency;
use App\Models\User;
use App\Services\Local\Helpers\CurrencyHelper;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WalletRepository
{
    /**
     * List of user wallets
     *
     * @param Model $model
     *
     * @return Collection
     */
    public function list(Model $model): Collection
    {
        return $this->query()
            ->where('holder_type', $model::class)
            ->where('holder_id', $model->id)
            ->where('slug', '<>', 'cp')
            ->get();
    }

    public function createWallet(
        User $user,
        Currency $currency,
    ): Wallet {
        return $user->createWallet([
            'name' => $currency->code ?? $user->currency->code,
            'slug' => $currency->slug ?? $user->currency->slug,
            'decimal_places' => CurrencyHelper::isCryptoCurrency($currency)
                ? 8 //todo: enough for crypto coins like BTC ?
                : 2,
            'meta' => [],
        ]);
    }

    public function createWalletCp(User $user): Wallet
    {
        return $user->createWallet([
            'name' => 'CP',
            'slug' => 'cp',
            'decimal_places' => 5,
        ]);
    }

    public function isSlugExist(Model $model, string $slug): bool
    {
        return (bool)$this->query()
            ->where('holder_type', $model::class) // todo: check
            ->where('holder_id', $model->id)
            ->where('slug', $slug)
            ->count(); // todo: exists() ?
    }

    public function query(): Builder
    {
        return Wallet::query();
    }

    public function setEstchangeData(
        int $walletId,
        string $address,
        string $clientId,
    ): bool {
        return $this->query()
            ->where('id', $walletId)
            ->update([
                'meta->estchange_client_id' => $clientId,
                'meta->address' => $address,
            ]);
    }

    public function setSimplepayData(
        int $walletId,
        string $address,
        string $clientId,
    ): bool {
        return $this->query()
            ->where('id', $walletId)
            ->update([
                'meta->simplepay_payment_id' => $clientId,
                'meta->address' => $address,
            ]);
    }

    public function getByAddress(string $address): ?Wallet
    {
        /** @var Wallet|Builder $query */
        $query = $this->query();

        return $query
            ->where('meta->address', $address)
            ->first();
    }
}
