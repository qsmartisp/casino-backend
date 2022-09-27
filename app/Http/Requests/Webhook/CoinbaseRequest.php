<?php

namespace App\Http\Requests\Webhook;

use App\Services\Coinbase\DTO\Webhook\RequestParamsDTO;
use App\Services\Coinbase\Enums\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property-read RequestParamsDTO dto
 * @property-read string webhook_slug
 */
class CoinbaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required', // todo: int

            'event' => 'required|array',
            'event.id' => 'required|string',
            'event.resource' => 'required|string|in:event',
            'event.type' => [
                'required',
                'string',
                new Enum(EventType::class),
            ],
            'event.data' => 'required|array',

            'event.data.code' => 'required|string',
            'event.data.hosted_url' => 'required|string',

            'event.data.metadata' => 'nullable|array',
            'event.data.pricing' => 'sometimes|nullable|array',
        ];
    }
}
