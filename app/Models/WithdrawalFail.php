<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property int withdrawal_id
 * @property string|null|Collection response_data
 */
class WithdrawalFail extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_id',
        'response_data',
    ];

    protected $casts = [
        'response_data' => AsCollection::class,
    ];
}
