<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Game
 */
class GameHistoryResource extends JsonResource
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
            'game_name' => $this->name,
            'spin_id' => $this->spin_id,
            'currency_code' => $this->currency_code,
            'balance_before' => number_format($this->balance_before, 2),
            'balance_after' => number_format($this->balance_after, 2),
            'balance_amount' => number_format($this->balance_amount, 2),
            'bet' => $this->bet,
            'cp' => $this->cp,
            'started_at' => $this->created_at,
        ];
    }
}
