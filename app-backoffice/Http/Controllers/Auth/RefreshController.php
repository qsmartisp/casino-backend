<?php

namespace AppBackoffice\Http\Controllers\Auth;

use AppBackoffice\Http\Controllers\Controller;
use AppBackoffice\Http\Resources\Auth\SigninResource;
use Carbon\Carbon;
use JsonSerializable;

class RefreshController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonSerializable
    {
        $token = $this->guard()->refresh();

        return new SigninResource([
            'access_token' => $token,
            'expires_at' => Carbon::now()->addMinutes(config('jwt.ttl')),
        ]);
    }

}
