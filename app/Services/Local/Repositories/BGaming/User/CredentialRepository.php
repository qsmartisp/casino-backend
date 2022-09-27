<?php

namespace App\Services\Local\Repositories\BGaming\User;

use App\Models\BGaming\User\Credential;
use App\Models\User;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

class CredentialRepository extends Repository
{
    public function createByUserInfo(User $user, string $login): Credential
    {
        return $this->query()->create([
            'user_id' => $user->id,
            'login' => $login,
        ]);
    }

    public function findByUser(User $user): ?Credential
    {
        return $this->query()->firstWhere(['user_id' => $user->id]);
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
