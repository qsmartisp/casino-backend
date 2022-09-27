<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddEnumValueSendingToColumnStatusInWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending', 'sending', 'accepted', 'rejected') NOT NULL AFTER transaction_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected') NOT NULL AFTER transaction_id");
    }
}
