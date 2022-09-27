<?php

namespace App\Http\Resources;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Level
 */
class LevelResource extends JsonResource
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
        $prevLevelNumber = $this->number > 1
            ? $this->number - 1
            : null;
        $nextLevelNumber = $this->number < Level::all()->max('number') // todo
            ? $this->number + 1
            : null;

        return [
            'prev' => $prevLevelNumber,
            'next' => $nextLevelNumber,
            'max_cp' => $nextLevelNumber ?
                Level::whereNumber($nextLevelNumber)->first()->cp // todo
                : null,

            'number' => $this->number,
            'prize_amount' => $this->prize_amount,
            'prize_type' => $this->prize_type,
            'min_cp' => $this->cp,
            'cp' => $this->cp,
        ];
    }
}
