<?php

namespace App\Http\Requests\Webhook;

use App\Enums\Simplepay\Webhook\Method;
use App\Services\Simplepay\DTO\Webhook\RequestDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property-read RequestDto dto
 * @property-read string webhook_slug
 */
class SimplepayRequest extends FormRequest
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
            'method' => [
                'required',
                'string',
                new Enum(Method::class),
            ],
            'id' => 'required|integer',
            'service_id' => 'required|integer',
            'amount' => 'required|numeric',
            'order' => 'required|string',
            'timestamp' => 'required|numeric',
            'attributes' => 'sometimes|nullable',
            'data' => 'sometimes|nullable',
            'hash' => 'required|string',
        ];
    }
}
