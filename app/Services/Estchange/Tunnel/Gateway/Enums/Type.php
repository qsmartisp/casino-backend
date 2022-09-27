<?php

namespace App\Services\Estchange\Tunnel\Gateway\Enums;

enum Type: string
{
    case All = 'all';
    case Transaction = 'transaction';
    case AutoconvertationDeposit = 'autoconvertation.deposit';
    case AutoconvertationTransfer = 'autoconvertation.transfer';
    case AutodepositDeposit = 'autodeposit.deposit';
    case AutodepositTransfer = 'autodeposit.transfer';
    case Autowithdrawal = 'autowithdrawal';
}
