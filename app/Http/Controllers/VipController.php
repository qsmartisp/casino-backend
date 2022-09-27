<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use App\Models\Status;
use JsonSerializable;

class VipController extends Controller
{
    /**
     * Get statuses list with levels
     *
     * @return JsonSerializable
     */
    public function index(): JsonSerializable
    {
        /** @var Status $statuses */
        $statuses = Status::query()
            ->with('levels')
            ->get();

        return StatusResource::collection($statuses);
    }

}
