<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('statuses')->truncate();

        DB::table('statuses')->insert([
            ['title' => 'Opal', 'prize' => '1800 FS'],
            ['title' => 'Sapphire', 'prize' => '150 EUR'],
            ['title' => 'Emerald', 'prize' => '735 EUR'],
            ['title' => 'Ruby', 'prize' => '5300 EUR'],
            ['title' => 'Diamond', 'prize' => '44400 EUR'],
        ]);
    }
}
