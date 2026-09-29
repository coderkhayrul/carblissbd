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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('use_type', ['Once', 'Multiple'])->default('Once');
            $table->enum('discount_type', ['Percentage', 'Fixed'])->default('Percentage');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
