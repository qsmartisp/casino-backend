<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('levels')->truncate();

        DB::table('levels')->insert([
            ['status_id' => 1, 'number' => 1, 'prize_amount' => null, 'prize_type' => null, 'cp' => 0],
            ['status_id' => 1, 'number' => 2, 'prize_amount' => 10, 'prize_type' => 'FS', 'cp' => 100],
            ['status_id' => 1, 'number' => 3, 'prize_amount' => 20, 'prize_type' => 'FS', 'cp' => 200],
            ['status_id' => 1, 'number' => 4, 'prize_amount' => 30, 'prize_type' => 'FS', 'cp' => 300],
            ['status_id' => 1, 'number' => 5, 'prize_amount' => 40, 'prize_type' => 'FS', 'cp' => 500],
            ['status_id' => 1, 'number' => 6, 'prize_amount' => 50, 'prize_type' => 'FS', 'cp' => 700],

            ['status_id' => 2, 'number' => 7, 'prize_amount' => 10, 'prize_type' => 'EUR', 'cp' => 900],
            ['status_id' => 2, 'number' => 8, 'prize_amount' => 15, 'prize_type' => 'EUR', 'cp' => 1300],
            ['status_id' => 2, 'number' => 9, 'prize_amount' => 20, 'prize_type' => 'EUR', 'cp' => 1800],
            ['status_id' => 2, 'number' => 10, 'prize_amount' => 30, 'prize_type' => 'EUR', 'cp' => 2500],
            ['status_id' => 2, 'number' => 11, 'prize_amount' => 35, 'prize_type' => 'EUR', 'cp' => 3000],
            ['status_id' => 2, 'number' => 12, 'prize_amount' => 40, 'prize_type' => 'EUR', 'cp' => 3500],

            ['status_id' => 3, 'number' => 13, 'prize_amount' => 55, 'prize_type' => 'EUR', 'cp' => 4500],
            ['status_id' => 3, 'number' => 14, 'prize_amount' => 70, 'prize_type' => 'EUR', 'cp' => 6000],
            ['status_id' => 3, 'number' => 15, 'prize_amount' => 100, 'prize_type' => 'EUR', 'cp' => 8000],
            ['status_id' => 3, 'number' => 16, 'prize_amount' => 120, 'prize_type' => 'EUR', 'cp' => 10000],
            ['status_id' => 3, 'number' => 17, 'prize_amount' => 150, 'prize_type' => 'EUR', 'cp' => 13000],
            ['status_id' => 3, 'number' => 18, 'prize_amount' => 240, 'prize_type' => 'EUR', 'cp' => 20000],

            ['status_id' => 4, 'number' => 19, 'prize_amount' => 350, 'prize_type' => 'EUR', 'cp' => 30000],
            ['status_id' => 4, 'number' => 20, 'prize_amount' => 500, 'prize_type' => 'EUR', 'cp' => 40000],
            ['status_id' => 4, 'number' => 21, 'prize_amount' => 600, 'prize_type' => 'EUR', 'cp' => 50000],
            ['status_id' => 4, 'number' => 22, 'prize_amount' => 850, 'prize_type' => 'EUR', 'cp' => 70000],
            ['status_id' => 4, 'number' => 23, 'prize_amount' => 1200, 'prize_type' => 'EUR', 'cp' => 100000],
            ['status_id' => 4, 'number' => 24, 'prize_amount' => 1800, 'prize_type' => 'EUR', 'cp' => 150000],

            ['status_id' => 5, 'number' => 25, 'prize_amount' => 2400, 'prize_type' => 'EUR', 'cp' => 200000],
            ['status_id' => 5, 'number' => 26, 'prize_amount' => 6000, 'prize_type' => 'EUR', 'cp' => 500000],
            ['status_id' => 5, 'number' => 27, 'prize_amount' => 12000, 'prize_type' => 'EUR', 'cp' => 1000000],
            ['status_id' => 5, 'number' => 28, 'prize_amount' => 24000, 'prize_type' => 'EUR', 'cp' => 2000000],
        ]);
    }
}
