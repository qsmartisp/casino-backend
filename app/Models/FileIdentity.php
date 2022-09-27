<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int id
 * @property int file_id
 * @property int identity_id
 * @property string identity_type
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class FileIdentity extends Pivot
{
    public $timestamps = false;
}
