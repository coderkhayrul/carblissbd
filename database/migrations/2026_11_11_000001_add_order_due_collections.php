<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_customer_payments', function (Blueprint $table) {
            $table->uuid('collection_token')->nullable()->unique();
            $table->string('collection_fingerprint',64)->nullable();
            $table->string('collection_reference',191)->nullable();
            $table->string('record_type',30)->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('superseded_at')->nullable();
            $table->decimal('paid_before',12,2)->nullable();
            $table->decimal('paid_after',12,2)->nullable();
            $table->decimal('due_after',12,2)->nullable();
            $table->text('note')->nullable();
            $table->unique(['order_id','payment_method','collection_reference'],'order_collection_reference_unique');
        });
    }
    public function down(): void
    {
        Schema::table('order_customer_payments', function (Blueprint $table) {
            $table->dropForeign(['collected_by']);
            $table->dropUnique('order_collection_reference_unique');
            $table->dropUnique(['collection_token']);
            $table->dropColumn(['collection_token','collection_fingerprint','collection_reference','record_type','collected_by','received_at','superseded_at','paid_before','paid_after','due_after','note']);
        });
    }
};
