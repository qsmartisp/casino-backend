<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoinNameToEstchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estchange_rates', function (Blueprint $table) {
            $table->string('coin_name')->nullable()->after('coin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estchange_rates', function (Blueprint $table) {
            $table->dropColumn('coin_name');
        });
    }
}
