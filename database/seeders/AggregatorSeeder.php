<?php

namespace Database\Seeders;

use App\Enums\Game\Aggregator\Name;
use App\Models\Aggregator;
use Illuminate\Database\Seeder;

class AggregatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (Name::cases() as $name) {
            Aggregator::query()->create([
                'name' => $name->value,
                'slug' => mb_strtolower($name->value),
            ]);
        }
    }
}
