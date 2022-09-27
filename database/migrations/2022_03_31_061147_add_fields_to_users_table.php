<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('disabled_at')->nullable();
            $table->enum('verification_status', ['unverified', 'verified', 'waiting'])->default('unverified');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('self_exclusion_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('disabled_at');
            $table->dropColumn('verification_status');
            $table->dropColumn('verified_at');
            $table->dropColumn('self_exclusion_until');
        });
    }
}
