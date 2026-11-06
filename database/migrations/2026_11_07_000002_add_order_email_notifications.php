<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->boolean('enabled')->default(false);
            $table->text('mail_password')->change();
        });
        foreach (\Illuminate\Support\Facades\DB::table('email_settings')->get(['id', 'mail_password']) as $settings) {
            if (!$settings->mail_password) continue;
            try { \Illuminate\Support\Facades\Crypt::decryptString($settings->mail_password); }
            catch (\Illuminate\Contracts\Encryption\DecryptException) {
                \Illuminate\Support\Facades\DB::table('email_settings')->where('id', $settings->id)->update(['mail_password' => \Illuminate\Support\Facades\Crypt::encryptString($settings->mail_password)]);
            }
        }
        Schema::create('order_email_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->string('recipient');
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->string('last_error')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('order_email_notifications');
        Schema::table('email_settings', fn (Blueprint $table) => $table->dropColumn('enabled'));
    }
};
