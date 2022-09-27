<?php

namespace App\Http\Middleware;

use App\Enums\Transaction\System;
use App\Services\Coinbase\DTO\Webhook\RequestParamsDTO as CoinbaseDTO;
use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\RequestParamsDTO as EstchangeDTO;
use App\Services\BGaming\DTO\Webhook\RequestDto as BGamingDTO;
use App\Services\Simplepay\DTO\Webhook\RequestDto as SimplepayDTO;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class MergeRequestDto
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param string $system
     *
     * @return Response|RedirectResponse
     *
     * @throws UnknownProperties
     */
    public function handle(Request $request, Closure $next, string $system)
    {
        $request->merge([
            'dto' => $this->getDto($request, System::from($system)),
        ]);

        return $next($request);
    }

    /**
     * @throws UnknownProperties
     */
    private function getDto(Request $request, System $system): DataTransferObject
    {
        return match ($system) {
            System::Coinbase => new CoinbaseDTO(
                request: $request->all(),
                signature: $request->header('x-cc-webhook-signature'),
            ),
            System::Estchange => new EstchangeDTO(
                request: $request->all(),
                signature: $request->header('x-signature'),
            ),
            System::BGaming => new BGamingDTO(
                params: $request->all(),
                signature: $request->header('x-request-sign'),
            ),
            System::Simplepay => new SimplepayDTO(
                params: $request->except('hash'),
                signature: $request->input('hash'),
            ),
        };
    }
}
