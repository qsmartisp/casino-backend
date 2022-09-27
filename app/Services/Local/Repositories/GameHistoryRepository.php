<?php

namespace App\Services\Local\Repositories;

use App\Models\GameHistory;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

class GameHistoryRepository extends Repository
{
    public function query(): GameHistory|Builder
    {
        return GameHistory::query();
    }
}
