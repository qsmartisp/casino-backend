<?php

namespace App\Http\Controllers;

use App\Http\Resources\InitialResource;
use App\Services\Local\Repositories\Contracts\CountryRepository;
use App\Services\Local\Repositories\Contracts\CurrencyRepository;
use JsonSerializable;

class InitialController extends Controller
{
    public function __construct(
        protected CountryRepository $countryRepository,
        protected CurrencyRepository $currencyRepository,
    )
    {
    }

    public function __invoke(): JsonSerializable
    {
        $data = [
            'countries' => $this->countryRepository->all(),
            'currencies' => $this->currencyRepository->allByCode(['USD', 'EUR']),
        ];

        return new InitialResource($data);
    }
}
