<?php

namespace App\Enums\PersonalAccessToken;

enum Status: string
{
    case Active = 'active';
    case Expired = 'expired';
}
