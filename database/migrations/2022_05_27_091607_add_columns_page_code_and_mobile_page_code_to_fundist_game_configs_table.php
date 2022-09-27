<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsPageCodeAndMobilePageCodeToFundistGameConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('fundist_game_configs', static function (Blueprint $table) {
            $table->string('mobile_page_code')->nullable()->after('id');
            $table->string('page_code')->nullable()->after('id');
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
            $table->dropColumn('mobile_page_code');
            $table->dropColumn('page_code');
        });
    }
}
