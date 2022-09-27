<?php

namespace App\Models\BGaming\Game;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string slug
 * @property string category
 * @property string feature_group
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Config extends Model
{
    use HasFactory;

    protected $primaryKey = 'slug';

    protected $table = 'bgaming_game_configs';

    protected $fillable = [
        'slug',
        'category',
        'feature_group',
    ];
}
