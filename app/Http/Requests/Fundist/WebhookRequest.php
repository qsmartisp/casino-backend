<?php

namespace App\Http\Requests\Fundist;

use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
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
            'type' => 'required|string|in:ping,balance,debit,credit,roundinfo',
            'hmac' => 'required|string',

            'userid' => 'required_if:type,balance,credit|string',
            'currency' => 'required_if:type,balance,credit|string',
            'i_gamedesc' => 'required_if:type,balance,credit|string',

            'tid' => 'required_if:type,credit',
            'amount' => 'required_if:type,credit|numeric',
            'i_gameid' => 'required_if:type,credit|string',
            'i_actionid' => 'required_if:type,credit|string',

            'i_rollback' => 'sometimes|string',
            'subtype' => 'sometimes|string',

            // todo: make required
            'game_extra' => 'sometimes|string',
            'i_extparam' => 'sometimes|string',
            // todo: ???
            'mrid' => 'sometimes|string',
        ];
    }
}
