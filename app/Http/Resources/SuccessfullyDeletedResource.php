<?php

namespace App\Http\Resources;

class SuccessfullyDeletedResource extends SuccessResource
{
    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode(204);
    }
}
