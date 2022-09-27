<?php

namespace App\Http\Requests\Deposit;

use App\Services\Estchange\Tunnel\Gateway\Enums\Coin;
use App\Services\Estchange\Tunnel\Gateway\Enums\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property string currency
 * @property string coin
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
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'currency' => 'required|string|exists:currencies,code',
            'coin' => [
                'required',
                'string',
                new Enum(Coin::class),
            ],
        ];
    }
}
