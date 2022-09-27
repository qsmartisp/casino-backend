<?php

namespace Database\Seeders;

use App\Models\GameLog;
use Illuminate\Database\Seeder;

class GameLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GameLog::factory()->count(30)->create();
    }
}
