<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string slug
 * @property string name
 *
 * @property Collection|File[] images
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
    ];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'file_identity',
            'identity_id',
            'file_id',
        )->where('identity_type', '=', Provider::class);
    }
}
