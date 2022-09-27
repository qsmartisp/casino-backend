<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGameHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_histories', function (Blueprint $table) {
            $table->renameColumn('game_name', 'name');
            $table->renameColumn('game_action_id', 'spin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_histories', function (Blueprint $table) {
            $table->renameColumn('name', 'game_name');
            $table->renameColumn('spin_id', 'game_action_id');
        });
    }
}

