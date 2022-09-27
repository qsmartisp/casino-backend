<?php

namespace App\Enums\Withdrawal;

enum Status: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Sending = 'sending';
    case Finished = 'finished';

    case Rejected = 'rejected';
    case Failed = 'failed';
}
