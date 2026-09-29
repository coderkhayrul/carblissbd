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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('short_description')->nullable();
            $table->string('button_title')->nullable();
            $table->string('button_icon')->nullable();
            $table->string('button_url')->nullable();
            $table->string('button_status')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
