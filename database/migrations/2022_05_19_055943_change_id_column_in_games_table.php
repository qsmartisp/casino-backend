<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdColumnInGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('games', static function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });
        Schema::table('games', static function (Blueprint $table) {
            $table->id()->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('games', static function (Blueprint $table) {
            $table->dropPrimary();
            $table->unsignedBigInteger('id')->change();
        });
        Schema::table('games', static function (Blueprint $table) {
            $table->primary('id');
        });
    }
}
