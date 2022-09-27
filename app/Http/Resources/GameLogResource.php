<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Game
 */
class GameLogResource extends JsonResource
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
            'game_id' => $this->game_id,
            'game_name' => $this->game_name,
            'currency_id' => $this->currency_id,
            'currency_code' => $this->currency_code,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'balance_amount' => $this->balance_amount,
            'bet' => $this->bet,
            'cp' => $this->cp,
            'started_at' => $this->created_at,
        ];
    }
}
