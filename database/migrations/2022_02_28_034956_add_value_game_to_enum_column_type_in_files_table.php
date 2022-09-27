<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * @link https://stackoverflow.com/questions/33496518/how-to-change-enum-type-column-in-laravel-migration
 */
class AddValueGameToEnumColumnTypeInFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN type ENUM('primary', 'selfie', 'payment', 'address', 'game') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN type ENUM('primary', 'selfie', 'payment', 'address') NOT NULL");
    }
}
