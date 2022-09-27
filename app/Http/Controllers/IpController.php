<?php

namespace App\Http\Controllers;

use App\Http\Resources\IpResource;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use JsonSerializable;

class IpController extends Controller
{
    public function __invoke(Request $request, Reader $reader): JsonSerializable
    {
        try {
            return new IpResource($reader->country($request->ip()));
        } catch (\Exception) {
            // todo: fix magic
            return abort(404, 'Invalid argument or address not found.');
        }
    }
}
