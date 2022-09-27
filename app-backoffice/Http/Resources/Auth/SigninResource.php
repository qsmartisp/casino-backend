<?php

namespace AppBackoffice\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SigninResource extends JsonResource
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
            'access_token' => $this['access_token'],
            'expires_at' => $this['expires_at'],
        ];
    }
}
