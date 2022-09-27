<?php

namespace App\Services\Game;

use App\Enums\Game\Aggregator\Name;
use App\Models\Game;
use App\Services\Game\Integrations\BGamingIntegration;
use App\Services\Game\Integrations\FundistIntegration;

class LauncherManager
{
    protected array $integrations;

    public function __construct(
        Integration ...$integrations,
    ) {
        $this->integrations = $integrations;
    }

    /**
     * @param Game $game
     *
     * @return FundistIntegration|BGamingIntegration|Integration
     */
    public function launcher(Game $game): Integration
    {
        return match ($game->aggregator->name) {
            Name::Fundist->value => $this->integrations[FundistIntegration::class],
            Name::BGaming->value => $this->integrations[BGamingIntegration::class],
        };
    }
}
