<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValueProviderToEnumColumnTypeInFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN type ENUM('primary', 'selfie', 'payment', 'address', 'game', 'provider') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN type ENUM('primary', 'selfie', 'payment', 'address', 'game') NOT NULL");
    }
}
