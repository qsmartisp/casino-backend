<?php

namespace App\Http\Requests\Deposit\Simplepay;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string amount
 * @property string currency
 */
class QrCodeRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'currency' => 'required|string|exists:currencies,code',
        ];
    }
}
