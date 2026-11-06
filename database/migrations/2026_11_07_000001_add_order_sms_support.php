<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('s_m_s_api_settings', function (Blueprint $table) {
            $table->boolean('enabled')->default(false);
            $table->string('request_method')->default('POST');
            $table->string('request_format')->default('form');
            $table->string('phone_format')->default('international');
            $table->text('request_parameters')->nullable();
            $table->text('request_headers')->nullable();
            $table->string('success_path')->nullable();
            $table->string('success_value')->nullable();
            $table->text('message_template')->nullable();
        });
        Schema::create('order_sms_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('error')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_sms_notifications');
        Schema::table('s_m_s_api_settings', fn (Blueprint $table) => $table->dropColumn([
            'enabled', 'request_method', 'request_format', 'phone_format', 'request_parameters',
            'request_headers', 'success_path', 'success_value', 'message_template',
        ]));
    }
};
