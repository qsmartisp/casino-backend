<?php

namespace App\Http\Requests\Webhook;

use App\Services\BGaming\DTO\Webhook\RequestDto;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read RequestDto dto
 * @property-read string webhook_slug
 */
class BGamingRequest extends FormRequest
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
            'user_id' => 'required|string',
            'currency' => 'required|string',
            'game' => 'required|string',
            'game_id' => 'sometimes|string|nullable',
            'finished' => 'sometimes|bool',
            'actions' => 'sometimes|array',
            'actions.*.action' => 'sometimes|string|in:bet,win',
            'actions.*.action_id' => 'sometimes|string', // unique
            'actions.*.amount' => 'sometimes|integer',
        ];
    }
}
