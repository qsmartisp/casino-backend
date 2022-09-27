<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            CurrencySeeder::class,
            PermissionSeeder::class,
            StatusSeeder::class,
            LevelSeeder::class,
            AggregatorSeeder::class,
            EstchangeRateSeeder::class,
            BinanceRateSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}
