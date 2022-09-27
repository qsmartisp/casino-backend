<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::drop('payment_logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::create('payment_logs', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('code')->index();
            $table->enum('type', ['deposit', 'withdraw']);
            $table->string('system_title');
            $table->string('status');
            $table->float('sum');
            $table->string('currency_code', 3);
            $table->timestamps();
        });
    }
}
