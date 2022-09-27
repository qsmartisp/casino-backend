<?php

namespace App\Http\Resources\Auth;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;

/**
 * @property NewAccessToken accessToken
 * @property NewAccessToken refreshToken
 */
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
        /** @var PersonalAccessToken $accessToken */
        $accessToken = $this->accessToken->accessToken;

        return [
            'access_token' => $this->accessToken->plainTextToken,
            'refresh_token' => $this->refreshToken->plainTextToken,
            'expires_at' => $accessToken->expires_at->getTimestamp(),
            'session_id' => $accessToken->session->id,
        ];
    }
}
