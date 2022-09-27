<?php

namespace AppBackoffice\Http\Controllers\Auth;

use AppBackoffice\Http\Controllers\Controller;
use AppBackoffice\Http\Resources\SuccessResource;
use Illuminate\Http\Request;
use JsonSerializable;

class SignoutController extends Controller
{
    public function __invoke(): JsonSerializable
    {
        $this->guard()->logout();

        return new SuccessResource();
    }
}
