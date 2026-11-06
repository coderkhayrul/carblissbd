<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('landing_page_feature')->nullable()->change();
            $table->text('landing_short_description')->nullable()->change();
            $table->longText('landing_long_description')->nullable()->change();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('entry_channel')->default('Checkout')->index();
            $table->unsignedBigInteger('landing_product_id')->nullable()->index();
            $table->text('landing_page_url')->nullable();
        });
        \Illuminate\Support\Facades\DB::table('orders')->where('order_source', 'GSale')->update(['entry_channel' => 'Admin']);
    }
    public function down(): void
    {
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['entry_channel', 'landing_product_id', 'landing_page_url']));
        // Keep expanded product text fields to preserve existing content.
    }
};
