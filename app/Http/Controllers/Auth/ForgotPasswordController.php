<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use JsonSerializable;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request): JsonSerializable
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink(
            $request->only('email')
        );

        return new SuccessResource([
            'success' => true,
        ]);
    }
}
