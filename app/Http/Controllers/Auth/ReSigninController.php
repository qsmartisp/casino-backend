<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\SigninResource;
use App\Models\Sanctum\PersonalAccessToken;
use App\Models\Sanctum\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JsonSerializable;

class ReSigninController extends Controller
{
    public function __invoke(Request $request): JsonSerializable
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            abort(401);
        }

        [$id, $token] = explode('|', $bearerToken);

        try {
            /** @var PersonalAccessToken $refreshToken */
            $refreshToken = PersonalAccessToken::query()
                ->where('id', $id)
                ->where('token', hash('sha256', $token))
                ->where('name', 'refresh_token')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(404, 'Token not found');
        }

        $accessToken = $refreshToken->tokenable->createToken('access_token');
        $refreshToken = $refreshToken->tokenable->createToken('refresh_token');

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
