<?php

namespace App\Http\Resources\Coinbase;

use App\Services\Coinbase\Responses\ChargeResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ChargeResponse
 */
class ChargeResource extends JsonResource
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
        // todo: discuss
        // return $this->asArray();
        return [
            'url' => $this->getUrl(),
            'code' => $this->getCode(),
        ];
    }
}
