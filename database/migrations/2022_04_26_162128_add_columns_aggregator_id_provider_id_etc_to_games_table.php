<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAggregatorIdProviderIdEtcToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('games', static function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id')->after('id');
            $table->unsignedBigInteger('aggregator_id')->after('id');

            $table->string('external_id')->after('id');
            $table->string('slug')->after('id');

            $table->json('meta')->nullable()->after('name');

            $table->unsignedBigInteger('sort_order')->nullable()->after('name');

            $table->enum('status', ['enabled', 'disabled'])->default('enabled')->after('name');

            $table->integer('max_bet')->nullable()->after('name');
            $table->integer('min_bet')->nullable()->after('name');

            $table->boolean('is_mobile')->default(true)->after('name');
            $table->boolean('is_desktop')->default(true)->after('name');
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
            $table->dropColumn('provider_id');
            $table->dropColumn('aggregator_id');

            $table->dropColumn('external_id');
            $table->dropColumn('slug');

            $table->dropColumn('meta');

            $table->dropColumn('sort_order');

            $table->dropColumn('status');

            $table->dropColumn('max_bet');
            $table->dropColumn('min_bet');

            $table->dropColumn('is_mobile');
            $table->dropColumn('is_desktop');
        });
    }
}
