<?php

namespace App\Http\Resources\Estchange;

use App\Models\Estchange\EstchangeRate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EstchangeRate
 */
class EstchangesRateResource extends JsonResource
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
            'currency' => $this->currency,
            'coin' => $this->coin,
            'coin_name' => $this->coin_name,
            'rate' => $this->rate,
        ];
    }
}
