<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Http\Controllers\InitialController
 */
class InitialControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * /api/init
     */
    public function testInitialRoute()
    {
        $response = $this->getJson('/api/init');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'countries',
                    'currencies',
                ],
            ]);
    }
}
