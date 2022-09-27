<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('estchange_rates')->truncate();

        DB::table('estchange_rates')->insert([
            ['currency' => 'USD', 'coin' => 'BTC', 'coin_name' => 'BTC'],
            ['currency' => 'USD', 'coin' => 'ETH', 'coin_name' => 'ETH'],
            ['currency' => 'USD', 'coin' => 'USDT_ERC20', 'coin_name' => 'USDT ERC20'],
            ['currency' => 'USD', 'coin' => 'ETH_BEP20', 'coin_name' => 'ETH BEP20'],
            ['currency' => 'USD', 'coin' => 'USDC_BEP20', 'coin_name' => 'USDC BEP20'],
            ['currency' => 'USD', 'coin' => 'USDT_Polygon', 'coin_name' => 'USDT Polygon'],

            ['currency' => 'EUR', 'coin' => 'BTC', 'coin_name' => 'BTC'],
            ['currency' => 'EUR', 'coin' => 'ETH', 'coin_name' => 'ETH'],
            ['currency' => 'EUR', 'coin' => 'USDT_ERC20', 'coin_name' => 'USDT ERC20'],
            ['currency' => 'EUR', 'coin' => 'ETH_BEP20', 'coin_name' => 'ETH BEP20'],
            ['currency' => 'EUR', 'coin' => 'USDC_BEP20', 'coin_name' => 'USDC BEP20'],

        ]);
    }
}
