<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('currencies')->truncate();

        DB::table('currencies')->insert([
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'BTC', 'name' => 'Bitcoin'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan'],
            ['code' => 'JPY', 'name' => 'Japanese Yen'],
            ['code' => 'RUB', 'name' => 'Russian Ruble'],
        ]);
    }
}
