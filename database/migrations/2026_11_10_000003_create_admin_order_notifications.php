<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_order_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
        Schema::create('admin_order_notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('admin_order_notifications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('read_at');
            $table->unique(['notification_id', 'user_id'], 'admin_order_read_unique');
            $table->index(['user_id', 'notification_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('admin_order_notification_reads');
        Schema::dropIfExists('admin_order_notifications');
    }
};
