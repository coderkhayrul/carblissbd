<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->string('email')->nullable()->change());
        Schema::table('next_invoice_nos', fn (Blueprint $table) => $table->unique(['type', 'year']));
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_order_source_unique');
            $table->string('checkout_token', 36)->nullable()->unique();
            $table->string('public_token', 36)->nullable()->unique();
            $table->unsignedBigInteger('shipping_charge_setting_id')->nullable();
            $table->string('delivery_area')->nullable();
            $table->string('delivery_time')->nullable();
            $table->text('notes')->nullable();
        });
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('product_name')->nullable();
            $table->string('variation_name')->nullable();
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->json('attributes')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
        });
        Schema::table('order_customer_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('order_customer_infos', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
        Schema::table('order_details', fn (Blueprint $table) => $table->dropColumn([
            'product_name', 'variation_name', 'sku', 'image', 'attributes', 'unit_price', 'line_total',
        ]));
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn([
            'checkout_token', 'public_token', 'shipping_charge_setting_id', 'delivery_area', 'delivery_time', 'notes',
        ]));
        Schema::table('next_invoice_nos', fn (Blueprint $table) => $table->dropUnique(['type', 'year']));
        // Do not restore the invalid unique order_source constraint or disallow existing null emails.
    }
};
