<?php

namespace App\Services\Estchange\Tunnel\Gateway\Enums;

enum Coin: string
{
    case BTC = 'BTC';
    case ETH = 'ETH';
    case USDT_ERC20 = 'USDT_ERC20';
    case BNB = 'BNB';
    case USDT_BEP20 = 'USDT_BEP20';
    case USDC = 'USDC';
    case TUSD = 'TUSD';
    case GUSD = 'GUSD';
    case BUSD = 'BUSD';
    case DAI = 'DAI';
    case USDC_BEP20 = 'USDC_BEP20';
    case ETH_BEP20 = 'ETH_BEP20';
    case MATIC_Polygon = 'MATIC_Polygon';
    case USDT_Polygon = 'USDT_Polygon';
}
