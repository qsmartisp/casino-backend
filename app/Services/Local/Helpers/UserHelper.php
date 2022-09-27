<?php

namespace App\Services\Local\Helpers;

use App\Models\User;

class UserHelper
{
    public static function user(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }
}
