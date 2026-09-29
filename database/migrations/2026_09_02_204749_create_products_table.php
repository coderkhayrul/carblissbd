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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('bangla_name')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('bangla_sale_price', 10, 2);
            $table->decimal('mrp_price', 10, 2);
            $table->string('thumbnail_image')->nullable();
            $table->string('size_chart_image')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('is_variation')->default(false);
            $table->boolean('is_refundable')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->string('barcode')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('publish')->default(1);
            $table->text('features')->nullable();
            $table->text('bangla_features')->nullable();
            $table->text('short_description')->nullable();
            $table->text('bangla_short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('bangla_description')->nullable();
            $table->string('landing_page_title')->nullable();
            $table->string('landing_page_feature')->nullable();
            $table->string('landing_short_description')->nullable();
            $table->string('landing_long_description')->nullable();
            $table->longText('landing_youtube_link')->nullable();

            // SEO COLUMNS
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
