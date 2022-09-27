<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read null|string name
 * @property-read null|int provider_id
 * @property-read null|string tag
 * @property-read null|array provider_ids
 */
class GamesRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'sometimes|string|nullable',
            'provider_id' => 'sometimes|exists:providers,id',
            'tag' => 'sometimes|string|nullable',
            'provider_ids' => 'sometimes|array',
            'provider_ids.*' => 'sometimes|exists:providers,id',
        ];
    }
}
