<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('deposits', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');

            $table->string('currency');
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
            ]);

            $table->string('external_transaction_id')->nullable();
            $table->float('fee')->nullable();

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
        Schema::dropIfExists('deposits');
    }
}
