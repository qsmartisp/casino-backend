<?php

namespace App\Enums\Log\Webhook;

enum Slug: string
{
    // Payments
    case Coinbase = 'coinbase';
    case Estchange = 'estchange';
    case Simplepay = 'simplepay';

    // Games
    case Fundist = 'fundist';
    case BGaming = 'bgaming';
}
