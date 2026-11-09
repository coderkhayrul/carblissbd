<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('steadfast_settings', function (Blueprint $table) {
            $table->string('base_url')->default('https://portal.packzy.com/api/v1');
            $table->boolean('auth_blocked')->default(false);
            $table->timestamp('api_paused_until')->nullable();
        });
        Schema::table('steadfast_shipments', function (Blueprint $table) {
            $table->string('return_status')->nullable();
            $table->timestamp('provider_updated_at')->nullable();
            $table->timestamp('webhook_received_at')->nullable();
        });
        Schema::create('steadfast_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event_key', 64)->unique();
            $table->string('body_hash', 64);
            $table->string('notification_type', 100);
            $table->longText('payload');
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('retry_at')->nullable()->index();
            $table->timestamp('processed_at')->nullable();
            $table->string('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('steadfast_webhooks');
        Schema::table('steadfast_shipments', fn (Blueprint $table) => $table->dropColumn(['return_status', 'provider_updated_at', 'webhook_received_at']));
        Schema::table('steadfast_settings', fn (Blueprint $table) => $table->dropColumn(['base_url', 'auth_blocked', 'api_paused_until']));
    }
};
