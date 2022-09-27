<?php

namespace App\Http\Resources\Simplepay;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Services\Simplepay\Responses\QrCodeResponse
 */
class QrCodeResource extends JsonResource
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
            'raw' => $this->raw(),
            'base64' => $this->base64(),
            'fee' => $this->fee(),
            'amount' => $this->amount(),
            'currency' => $this->currency(),

            // TODO: may be delete this in prod
            'order' => $this->order(),
        ];
    }
}
