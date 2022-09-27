<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnIdInFundistGameConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('fundist_game_configs', static function (Blueprint $table) {
            $table->string('id')->after('slug')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('fundist_game_configs', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->after('slug')->change();
        });
    }
}
