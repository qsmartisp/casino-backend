<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\RequestParamsDTO;
use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\ResponseParamsDTO;
use JsonException;

/**
 * @mixin RequestParamsDTO|ResponseParamsDTO
 */
trait WebhookDtoStringify
{
    public function toString(): string
    {
        $values = $this->toArray();

        ksort($values);
        unset($values['hmac']);

        if (array_key_exists('actions', $values)) {
            $actions = $values['actions'];
            $action_value = [];

            foreach ($actions as &$a) {
                ksort($a);

                $action_value[] = implode('', array_values($a));
            }

            unset($a);

            $values['actions'] = implode('', $action_value);
        }

        return implode('', array_values($values));
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
