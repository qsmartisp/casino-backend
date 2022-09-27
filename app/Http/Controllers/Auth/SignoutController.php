<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use Illuminate\Http\Request;
use JsonSerializable;

class SignoutController extends Controller
{
    public function __invoke(Request $request): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return new SuccessResource();
    }
}
