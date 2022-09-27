<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('webhooks', static function (Blueprint $table) {
            $table->string('hmac')->primary();
            $table->string('tid')->nullable();
            $table->string('type')->nullable();
            $table->string('subtype')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->string('userid')->nullable();
            $table->string('i_gameid')->nullable();
            $table->string('i_actionid')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
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
        Schema::dropIfExists('webhooks');
    }
}
