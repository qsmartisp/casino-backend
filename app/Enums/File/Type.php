<?php

namespace App\Enums\File;

enum Type: string
{
    case Primary = 'primary';
    case Selfie = 'selfie';
    case Payment = 'payment';
    case Address = 'address';
    case Game = 'game';
    case Provider = 'provider';
}
