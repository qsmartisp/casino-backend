<?php

namespace App\Http\Resources\Game;

use App\Http\Resources\ProviderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Game
 */
class GameResource extends JsonResource
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
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'has_demo' => $this->has_demo,
            'image' => $this->images->first()
                ? 'storage/' . $this->images->first()->path
                : null,
            'provider' => ProviderResource::make($this->provider),
        ];
    }
}
