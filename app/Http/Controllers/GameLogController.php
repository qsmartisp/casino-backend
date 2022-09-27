<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameLogResource;
use App\Models\GameLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use JsonSerializable;

class GameLogController extends Controller
{
    public function index(Request $request): JsonSerializable
    {
        $user = $request->user();

        /** @var GameLog|Builder $query */
        $query = GameLog::query();

        $gameLogs = $query
            ->hasUserId($user->id)
            ->orderBy('created_at', 'DESC')
            ->simplePaginate('20');

        return GameLogResource::collection($gameLogs);
    }

}
