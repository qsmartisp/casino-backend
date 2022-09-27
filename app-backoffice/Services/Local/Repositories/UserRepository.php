<?php

namespace AppBackoffice\Services\Local\Repositories;

use App\Services\Local\Repositories\UserRepository as Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class UserRepository extends Repository
{
    public function filterUsers(?string $email): Builder
    {
        return $this->query()
            ->with([
                'country' => (static fn(BelongsTo $query) => $query->select(['id', 'name'])),
                'currency' => (static fn(BelongsTo $query) => $query->select(['id', 'code'])),
                'wallets'  => (static fn(MorphMany $query) => $query->where('slug', '<>', 'cp')),
            ])
            ->when($email, function (Builder $q, string $email) {
                return $q->where('email', $email);
            });


    }
}
