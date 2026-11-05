<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->json('gallery_images')->nullable());
        Schema::table('product_variations', fn (Blueprint $table) => $table->string('image')->nullable());
    }

    public function down(): void
    {
        Schema::table('product_variations', fn (Blueprint $table) => $table->dropColumn('image'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('gallery_images'));
    }
};
