<?php

namespace App\Enums\Verification;

enum Type: string
{
    case Unverified = 'unverified';
    case Verified = 'verified';
    case Waiting = 'waiting';
}
