<?php

namespace AppBackoffice\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use RuntimeException;
use Tymon\JWTAuth\JWTGuard;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private const GUARD_NAME = 'jwt';

    protected function guard(): Guard
    {
        $guard = auth()->guard(self::GUARD_NAME);

        if (!$guard instanceof JWTGuard) {
            throw new RuntimeException('Expected guard of type JWTGuard');
        }

        return $guard;
    }
}
