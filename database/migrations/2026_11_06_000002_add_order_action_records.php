<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', fn (Blueprint $table) => $table->string('courier_name', 50)->nullable()->change());
        Schema::create('order_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('Open');
            $table->timestamps();
        });
        Schema::create('order_return_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_detail_id')->constrained('order_details')->restrictOnDelete();
            $table->foreignId('return_reason_id')->constrained('return_reasons')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('request_token');
            $table->unsignedInteger('quantity');
            $table->decimal('amount', 12, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['request_token', 'order_detail_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_return_records');
        Schema::dropIfExists('order_complaints');
        // Keep courier_name as a string so newly supported courier names remain valid.
    }
};
