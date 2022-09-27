<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JsonSerializable;

class ResetPasswordController extends Controller
{
    public function reset(Request $request): JsonSerializable
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return new SuccessResource([
            'success' => $status === Password::PASSWORD_RESET,
            'message' => __($status)
        ]);
    }

    public function update(Request $request): JsonSerializable
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8||different:old_password',
        ]);

        /** @var User $user */
        $user = $request->user();


        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->forceFill([
                'password' => Hash::make($request->input('password'))
            ])->setRememberToken(Str::random(60));

            $user->save();

            return new SuccessResource([
                'success' => true,
                'message' => 'Password was updated.'
            ]);
        }

        return new SuccessResource([
            'success' => false,
            'message' => 'Password does not match.'
        ]);
    }
}
