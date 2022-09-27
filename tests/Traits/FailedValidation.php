<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait FailedValidation
{
    /**
     * @throws \Throwable
     */
    protected function responseValidationFailedTest(TestResponse $response): void
    {
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);

        $errors = $response->decodeResponseJson()['errors'];

        foreach ($errors as $property => $array) {
            $this->assertIsString($property);
            $this->assertIsArray($array);

            foreach ($array as $item) {
                $this->assertIsString($item);
            }
        }
    }
}