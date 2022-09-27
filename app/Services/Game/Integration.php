<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\User;

interface Integration
{
    public function launch(string $ip, Game $game, User $user): Launch;

    public function launchDemo(string $ip, Game $game): Launch;
}
