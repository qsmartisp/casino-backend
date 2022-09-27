<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Wallet
 */
class WalletResource extends JsonResource
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
            'currency' => $this->name,
            'balance' => $this->balanceFloat,
            'is_default' => $this->name === $request->user()->currency->code,
            'withdrawable' => 0,
            'block_by_bonuses' => 0,
        ];
    }
}
