<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDeprecatedColumnsFromGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('games', static function (Blueprint $table) {
            $table->dropColumn('system_id');
            $table->dropColumn('subsystem_id');
            $table->dropColumn('page_code');
            $table->dropColumn('url');
            $table->dropColumn('mobile_url');
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
            $table->unsignedBigInteger('subsystem_id')->nullable()->after('id');
            $table->unsignedBigInteger('system_id')->after('id');

            $table->string('mobile_url')->nullable()->after('name');
            $table->string('url')->nullable()->after('name');
            $table->string('page_code')->nullable()->after('name');
        });
    }
}
