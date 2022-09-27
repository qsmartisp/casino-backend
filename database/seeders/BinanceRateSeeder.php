<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BinanceRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('binance_rates')->truncate();

        DB::table('binance_rates')->insert([
            ['currency' => 'EUR', 'symbol' => 'EUREUR', 'rate' => 1],
            ['currency' => 'USD', 'symbol' => 'EURUSDT', 'rate' => null],
            ['currency' => 'BTC', 'symbol' => 'EURBTC', 'rate' => 0.000035420074737],
            ['currency' => 'CNY', 'symbol' => 'EURCNY', 'rate' => 7.1623067],
            ['currency' => 'JPY', 'symbol' => 'EURJPY', 'rate' => 143.71819],
            ['currency' => 'RUB', 'symbol' => 'EURRUB', 'rate' => 64.867948],
        ]);
    }
}
