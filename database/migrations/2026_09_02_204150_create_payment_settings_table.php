<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('outside_dhaka_cod')->default(true);
            // BKASH PAYMENT SETTINGS
            $table->boolean('bkash_active')->default(false);
            $table->string('bkash_app_key')->nullable();
            $table->string('bkash_app_secret')->nullable();
            $table->string('bkash_username')->nullable();
            $table->string('bkash_password')->nullable();
            $table->string('bkash_url')->nullable();
            // SSL STORE PAYMENT SETTINGS
            $table->boolean('ssl_active')->default(false);
            $table->string('ssl_store_id')->nullable();
            $table->string('ssl_store_password')->nullable();
            $table->string('ssl_store_url')->nullable();
            $table->string('ssl_store_callback_url')->nullable();
            $table->string('ssl_store_failure_url')->nullable();
            $table->boolean('ssl_is_localhost')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
