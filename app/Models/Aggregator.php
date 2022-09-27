<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string slug
 * @property string name
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Collection|Provider[] providers
 */
class Aggregator extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
    ];

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class);
    }
}
