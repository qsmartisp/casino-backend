<?php

namespace App\Services\Local\Repositories\Contracts;

interface CurrencyRepository
{
    public function all(array $columns = ['*']);

    public function allByCode(array $codes);
}
