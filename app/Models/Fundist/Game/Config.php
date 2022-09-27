<?php

namespace App\Models\Fundist\Game;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string slug
 * @property string id
 * @property null|string $page_code
 * @property null|string $mobile_page_code
 * @property int system_id
 * @property int subsystem_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Config extends Model
{
    use HasFactory;

    protected $primaryKey = 'slug';

    protected $table = 'fundist_game_configs';

    protected $fillable = [
        'slug',
        'id',
        'page_code',
        'mobile_page_code',
        'system_id',
        'subsystem_id',
    ];
}
