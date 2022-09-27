<?php

namespace App\Services\Local\Repositories\Fundist\User;

use App\Models\Fundist\User\Credential;
use App\Models\User;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

class CredentialRepository extends Repository
{
    public function createByUserInfo(
        User $user,
        string $login,
        string $password,
    ): Credential {
        return $this->query()->create([
            'currency_id' => $user->currency->id,
            'user_id' => $user->id,
            'login' => $login,
            'password' => $password,
        ]);
    }

    public function findByUser(User $user): ?Credential
    {
        return $this->query()->firstWhere([
            'currency_id' => $user->currency->id,
            'user_id' => $user->id,
        ]);
    }

    public function findByLogin(string $login): ?Credential
    {
        /** @var Builder|Credential $query */
        $query = $this->query()
            ->with(['user']);

        return $query
            ->where('login', $login)
            ->first();
    }

    public function query(): Credential|Builder
    {
        return Credential::query();
    }
}
