<?php

namespace AppBackoffice\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'language' => null,
            'country' => [
                'id' => $this->country->id,
                'name' => $this->country->name,
            ],
            'verification_status' => $this->verification_status,
            'wallets' => WalletResource::collection($this->wallets),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
