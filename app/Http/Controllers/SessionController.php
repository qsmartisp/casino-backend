<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Http\Resources\SuccessfullyDeletedResource;
use App\Models\Sanctum\PersonalAccessToken;
use App\Models\Sanctum\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JsonSerializable;

class SessionController extends Controller
{
    public function list(Request $request): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        return SessionResource::collection($user->sessions);
    }

    public function dropAll(Request $request): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        $user->sessions()->delete();
        $user->tokens()->delete();

        return new SuccessfullyDeletedResource();
    }

    public function drop(Request $request): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        $user->sessions()
            ->where('access_token_id', $user->currentAccessToken()->id)
            ->delete();

        $user->currentRfreshToken()->delete();
        $user->currentAccessToken()->delete();

        return new SuccessfullyDeletedResource();
    }

    public function dropById(Request $request, int $id): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        try {
            /** @var Session $session */
            $session = Session::query()
                ->where('id', $id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(404, 'Session not found');
        }

        $access_token_id = $session->access_token_id;
        $refresh_token_id = $session->refresh_token_id;

        $session->delete();

        PersonalAccessToken::query()
            ->whereIn('id', [
                $access_token_id,
                $refresh_token_id,
            ])
            ->delete();

        return new SuccessfullyDeletedResource();
    }
}
