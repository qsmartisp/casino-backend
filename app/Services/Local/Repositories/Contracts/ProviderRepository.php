<?php

namespace App\Services\Local\Repositories\Contracts;

interface ProviderRepository
{
    public function all(array $columns = ['*'], array $with = []);
}
