<?php

namespace AppBackoffice\Http\Controllers\Auth;

use AppBackoffice\Http\Requests\Auth\SigninRequest;
use AppBackoffice\Http\Controllers\Controller;
use AppBackoffice\Http\Resources\Auth\SigninResource;
use Carbon\Carbon;
use JsonSerializable;

class SigninController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SigninRequest $request): JsonSerializable
    {
        $credentials = $request->validated();

        if (!$token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return new SigninResource([
            'access_token' => $token,
            'expires_at' => Carbon::now()->addMinutes(config('jwt.ttl')),
        ]);
    }

}
