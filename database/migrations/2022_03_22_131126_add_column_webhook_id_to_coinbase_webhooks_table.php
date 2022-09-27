<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWebhookIdToCoinbaseWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('coinbase_webhooks', static function (Blueprint $table) {
            $table->uuid('webhook_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('coinbase_webhooks', static function (Blueprint $table) {
            $table->dropColumn('webhook_id');
        });
    }
}
