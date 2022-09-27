<?php

namespace App\Http\Requests\Webhook;

use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\RequestParamsDTO;
use App\Services\Estchange\Tunnel\Gateway\Enums\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property-read RequestParamsDTO dto
 * @property-read string webhook_slug
 */
class EstchangeRequest extends FormRequest
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
            'transactionId' => 'required_if:type,transaction,autoconvertation.deposit,autoconvertation.transfer,autodeposit.deposit,autodeposit.transfer|string',
            'type' => [
                'required',
                'string',
                new Enum(Type::class),
            ],
            'address' => 'sometimes|string',
        ];
    }
}
