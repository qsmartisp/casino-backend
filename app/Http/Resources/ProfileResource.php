<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class ProfileResource extends JsonResource
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
            'nickname' => $this->nickname,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,

            'country' => [
                'id' => $this->country->id,
                'name' => $this->country->name,
            ],

            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'subscription_by_email' => $this->subscription_by_email,
            'subscription_by_sms' => $this->subscription_by_sms,

            'default_currency' => [
                'id' => $this->currency->id,
                'code' => $this->currency->code,
                'name' => $this->currency->name,
            ],

            'balance' => $this->getWallet($this->currency->code)?->balanceFloat,

            'cp' => $this->cpBalance,

            'status' => StatusCompactResource::make($this->level->status),
            'level' => LevelResource::make($this->level),
            'wallets' => WalletResource::collection($this->wallets),

            'is_disabled' => $this->is_disabled,
            'verification_status' => $this->verification_status,
            'self_exclusion_until' => $this->self_exclusion_until,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
