<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pathao_settings', function (Blueprint $table) {
            $table->boolean('webhook_enabled')->default(false);
            $table->text('webhook_secret')->nullable();
            $table->timestamp('webhook_verified_at')->nullable();
        });
        Schema::table('pathao_shipments', function (Blueprint $table) {
            $table->timestamp('provider_updated_at')->nullable();
            $table->timestamp('webhook_received_at')->nullable();
            $table->timestamp('metadata_updated_at')->nullable();
            $table->timestamp('settlement_updated_at')->nullable();
            $table->string('last_webhook_event')->nullable();
            $table->string('return_consignment_id')->nullable();
            $table->string('return_type')->nullable();
            $table->decimal('collected_amount', 12, 2)->nullable();
            $table->string('invoice_id')->nullable();
            $table->text('courier_reason')->nullable();
        });
        Schema::create('pathao_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event_key', 64)->unique();
            $table->string('event', 100)->index();
            $table->string('environment');
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
        Schema::dropIfExists('pathao_webhooks');
        Schema::table('pathao_shipments', fn (Blueprint $table) => $table->dropColumn(['provider_updated_at', 'webhook_received_at', 'metadata_updated_at', 'settlement_updated_at', 'last_webhook_event', 'return_consignment_id', 'return_type', 'collected_amount', 'invoice_id', 'courier_reason']));
        Schema::table('pathao_settings', fn (Blueprint $table) => $table->dropColumn(['webhook_enabled', 'webhook_secret', 'webhook_verified_at']));
    }
};
