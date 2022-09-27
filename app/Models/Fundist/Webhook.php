<?php

namespace App\Models\Fundist;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string hmac
 * @property string|null tid
 * @property string|null type
 * @property string|null subtype
 * @property string|null currency
 * @property string|null amount
 * @property string|null userid
 * @property string|null i_gameid
 * @property string|null i_actionid
 * @property string|null|Collection request_data
 * @property string|null|Collection response_data
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Webhook extends Model
{
    protected $primaryKey = 'hmac';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'hmac',
        'tid',
        'type',
        'subtype',
        'currency',
        'amount',
        'userid',
        'i_gameid',
        'i_actionid',
        'request_data',
        'response_data',
    ];

    protected $casts = [
        'request_data' => AsCollection::class,
        'response_data' => AsCollection::class,
    ];
}
