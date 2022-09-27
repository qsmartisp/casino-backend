<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait SuccessResponse
{
    protected function successResponseTest(TestResponse $response): void
    {
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'success' => true,
                ],
            ]);
    }
}