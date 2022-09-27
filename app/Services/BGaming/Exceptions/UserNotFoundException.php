<?php

namespace App\Services\BGaming\Exceptions;

class UserNotFoundException extends BGamingException
{
    protected const MESSAGE = "User Not Found";
}
