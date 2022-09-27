<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Services\Local\Repositories\Contracts\ProviderRepository;
use JsonSerializable;

class ProviderController extends Controller
{
    public function __construct(
        protected ProviderRepository $repository,
    ) {
    }

    public function list(): JsonSerializable
    {
        return ProviderResource::collection($this->repository->all(['id', 'slug', 'name'], ['images']));
    }
}
