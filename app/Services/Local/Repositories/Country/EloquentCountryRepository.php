<?php

namespace App\Services\Local\Repositories\Country;

use App\Models\Country;
use App\Services\Local\Repositories\Contracts\CountryRepository;

class EloquentCountryRepository implements CountryRepository
{
    public function all(array $columns = ['*'])
    {
        return Country::all();
    }
}
