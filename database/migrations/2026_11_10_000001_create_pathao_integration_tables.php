<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pathao_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->boolean('enabled')->default(false);
            $table->string('environment')->default('production');
            $table->text('client_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('api_paused_until')->nullable();
            $table->boolean('auth_blocked')->default(false);
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedInteger('delivery_type')->default(48);
            $table->unsignedTinyInteger('item_type')->default(2);
            $table->decimal('item_weight', 5, 2)->default(0.5);
            $table->json('stores')->nullable();
            $table->json('status_mapping')->nullable();
            $table->timestamps();
        });
        Schema::create('pathao_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->restrictOnDelete();
            $table->string('merchant_order_id', 100)->unique();
            $table->string('consignment_id')->nullable()->unique();
            $table->string('environment');
            $table->unsignedBigInteger('store_id');
            $table->string('booking_state')->default('booking')->index();
            $table->string('order_status')->nullable();
            $table->unsignedInteger('amount_to_collect');
            $table->decimal('delivery_fee', 12, 2)->nullable();
            $table->longText('payload');
            $table->string('last_error')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
        Schema::create('pathao_shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('pathao_shipments')->cascadeOnDelete();
            $table->string('source');
            $table->string('order_status');
            $table->unsignedBigInteger('mapped_status_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pathao_shipment_events');
        Schema::dropIfExists('pathao_shipments');
        Schema::dropIfExists('pathao_settings');
    }
};
