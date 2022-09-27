<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUrlAndMobileUrlToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('games', static function (Blueprint $table) {
            $table->string('mobile_url')->after('name');
            $table->string('url')->after('name');
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
            $table->dropColumn('mobile_url');
            $table->dropColumn('url');
        });
    }
}
