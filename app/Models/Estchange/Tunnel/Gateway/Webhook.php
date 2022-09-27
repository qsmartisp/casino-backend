<?php

namespace App\Models\Estchange\Tunnel\Gateway;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string webhook_id
 * @property string|null|Collection request_data
 * @property string|null|Collection response_data
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Webhook extends Model
{
    protected $table = 'estchange_webhooks';

    protected $fillable = [
        'webhook_id',
        'request_data',
        'response_data',
    ];

    protected $casts = [
        'request_data' => AsCollection::class,
        'response_data' => AsCollection::class,
    ];
}
