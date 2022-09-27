<?php

namespace App\Services\Local\Repositories;

use App\Models\User;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends Repository
{
    public function query(): User|Builder
    {
        return User::query();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findById(int $id): User
    {
        return $this->query()->findOrFail($id);
    }

    public function getById(int $id): ?User
    {
        return $this->query()->find($id);
    }
}
