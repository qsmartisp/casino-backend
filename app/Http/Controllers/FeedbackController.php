<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feedback\StoreRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(StoreRequest $request): FeedbackResource
    {
        $feedback = Feedback::query()->create([
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'ip' => $request->ip(),
        ]);

        return new FeedbackResource($feedback);
    }
}
