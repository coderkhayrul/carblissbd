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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bangla_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('image')->nullable();
            $table->integer('serial')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('show_in_quick_links')->default(false);

            // SEO TABLES
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
