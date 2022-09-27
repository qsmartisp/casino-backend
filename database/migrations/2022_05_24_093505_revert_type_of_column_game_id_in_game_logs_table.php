<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RevertTypeOfColumnGameIdInGameLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared("UPDATE game_logs gl, games g SET gl.game_id = g.id WHERE g.external_id = gl.game_id");
        DB::unprepared("UPDATE IGNORE game_logs SET game_id = CONVERT(game_id, UNSIGNED INTEGER)");

        Schema::table('game_logs', static function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->after('user_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('game_logs', static function (Blueprint $table) {
            $table->string('game_id')->after('user_id')->change();
        });

        DB::unprepared("UPDATE game_logs gl, games g SET gl.game_id = g.external_id WHERE g.id = gl.game_id");
    }
}
