<?php

namespace App\Services\BGaming\Responses;

use App\Services\BGaming\Response;

class LaunchOptionsResponse extends Response
{
    public function asArray(): array
    {
        return [
            'content' => $this->getGameUrl(),
        ];
    }

    public function getGameUrl(): string
    {
        return $this->getLaunchOptions()['game_url'];
    }

    public function getStrategy(): string
    {
        return $this->getLaunchOptions()['strategy'];
    }

    public function getSessionId(): string
    {
        return $this->getDecodedResponse()['session_id'];
    }

    protected function getLaunchOptions(): array
    {
        return $this->getDecodedResponse()['launch_options'];
    }
}
