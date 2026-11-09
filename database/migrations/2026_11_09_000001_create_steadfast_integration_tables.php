<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('steadfast_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->boolean('enabled')->default(false);
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('webhook_token');
            $table->json('status_mapping')->nullable();
            $table->timestamps();
        });
        Schema::create('steadfast_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->restrictOnDelete();
            $table->string('invoice')->unique();
            $table->string('consignment_id')->nullable()->unique();
            $table->string('tracking_code')->nullable();
            $table->string('booking_state')->default('booking')->index();
            $table->string('delivery_status')->nullable();
            $table->decimal('cod_amount', 12, 2);
            $table->string('last_error')->nullable();
            $table->timestamp('synced_at')->nullable()->index();
            $table->timestamp('checked_at')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('steadfast_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('steadfast_shipments')->cascadeOnDelete();
            $table->string('source');
            $table->string('delivery_status');
            $table->unsignedBigInteger('mapped_status_id')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('steadfast_events');
        Schema::dropIfExists('steadfast_shipments');
        Schema::dropIfExists('steadfast_settings');
    }
};
