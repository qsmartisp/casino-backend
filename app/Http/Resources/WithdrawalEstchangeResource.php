<?php

namespace App\Http\Resources;

use App\Http\Resources\Estchange\EstchangesRateResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Currency
 */
class WithdrawalEstchangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'currency' => $this->code,
            'rates' => EstchangesRateResource::collection($this->estchangeRates),
        ];
    }
}
