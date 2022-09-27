<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int id
 * @property int file_id
 * @property int verification_request_id
 *
 */
class FileVerificationRequest extends Pivot
{
    public $timestamps = false;
}
