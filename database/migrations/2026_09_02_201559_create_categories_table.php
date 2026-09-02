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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('banner_image')->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->boolean('show_on_menu')->default(false);
            $table->boolean('show_on_side_menu')->default(false);
            $table->boolean('is_tab')->default(false);
            $table->integer('menu_serial')->default(0);
            $table->integer('tab_serial')->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('is_top_ten')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
