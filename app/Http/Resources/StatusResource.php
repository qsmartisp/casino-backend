<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Status
 */
class StatusResource extends JsonResource
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
            'title' => $this->title,
            'prize' => $this->prize,
            'min_cp' => $this->min_cp,
            'max_cp' => $this->max_cp,
            'levels' => LevelResource::collection($this->levels),
        ];
    }
}
