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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('extra_shipping_cost_id')->nullable();
            $table->date('date');
            $table->date('delivery_date')->nullable();
            $table->string('order_no')->unique();
            $table->enum('order_source', ['Website', 'GSale', 'App'])->unique();
            $table->enum('payment_type', ['COD', 'Online'])->default('COD');
            $table->enum('shipping_method', ['Regular Shipping', 'Store Pickup'])->default('Regular Shipping');
            $table->decimal('total_quantity', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total_vat_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('affiliate_discount', 10, 2)->default(0);
            $table->decimal('extra_shipping_cost_amount', 10, 2)->default(0);
            $table->decimal('coupon_discount_amount', 10, 2)->default(0);
            $table->decimal('extra_discount_amount', 10, 2)->default(0);
            $table->decimal('total_shipping_cost', 10, 2)->default(0);
            $table->decimal('total_discount_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->string('draft_source')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->unsignedBigInteger('current_status')->default(1);
            $table->string('courier_status')->nullable();
            $table->enum('courier_name', ['Pathao ', 'Steadfast', 'RedX'])->nullable();
            $table->boolean('is_courier_assigned')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
