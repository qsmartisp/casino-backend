<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExternalWithdrawalIdToWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('withdrawals', static function (Blueprint $table) {
            $table->string('external_withdrawal_id')->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('withdrawals', static function (Blueprint $table) {
            $table->dropColumn('external_withdrawal_id');
        });
    }
}
