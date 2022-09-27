<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundistGameConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('fundist_game_configs', static function (Blueprint $table) {
            $table->string('slug')->primary();

            $table->unsignedBigInteger('id');

            $table->unsignedBigInteger('system_id');
            $table->unsignedBigInteger('subsystem_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('fundist_game_configs');
    }
}
