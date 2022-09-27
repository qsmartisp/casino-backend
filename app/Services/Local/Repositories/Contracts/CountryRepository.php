<?php

namespace App\Services\Local\Repositories\Contracts;

interface CountryRepository
{
    public function all(array $columns = ['*']);
}
