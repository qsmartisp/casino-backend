<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SigninRequest;
use App\Http\Resources\Auth\SigninResource;
use App\Models\Sanctum\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JsonSerializable;

class SigninController extends Controller
{
    public function __invoke(SigninRequest $request): JsonSerializable
    {
        $data = $request->validated();

        /** @var User $user */
        $user = User::query()
            ->where('email', $data['email'])
            ->firstOrFail();

        if (!Hash::check($data['password'], $user->password)) {
            return abort(403, 'Incorrect password.');
        }

        if ($user && $user->is_disabled) {
            return abort(403, 'User disabled.');
        }

        if ($user && $user->self_exclusion_until) {
            return abort(403, 'User self-excluded.');
        }

        $accessToken = $user->createToken('access_token');
        $refreshToken = $user->createToken('refresh_token');

        Session::query()->create([
            'access_token_id' => $accessToken->accessToken->id,
            'refresh_token_id' => $refreshToken->accessToken->id,
            'ip' => $request->ip(),
            'browser' => $request->userAgent(),

            // todo: Detect country
            'country_id' => 1,
        ]);

        return new SigninResource(
            (object)[
                'accessToken' => $accessToken,
                'refreshToken' => $refreshToken,
            ]
        );
    }
}
