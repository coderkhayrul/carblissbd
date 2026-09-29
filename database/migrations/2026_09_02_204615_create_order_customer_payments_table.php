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
        Schema::create('order_customer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('payment_method', ['Cash', 'Bkash', 'Nagad', 'Rocket', 'Bank Transfer'])->default('Cash');
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed'])->default('Pending');
            $table->decimal('amount', 10, 2);
            $table->string('payment_tnx_no')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_customer_payments');
    }
};
