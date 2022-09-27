<?php

namespace App\Enums\Transaction;

enum System: string
{
    // Payments
    case Coinbase = 'coinbase';
    case Estchange = 'estchange';
    case Simplepay = 'simplepay';

    // Games
    case Fundist = 'fundist';
    case BGaming = 'bgaming';
}
