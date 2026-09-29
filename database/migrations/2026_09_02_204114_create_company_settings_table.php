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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('phone');
            $table->string('hotline')->nullable();
            $table->string('email');
            $table->text('address');
            $table->string('logo');
            $table->string('favicon');
            $table->string('admin_favicon')->nullable();
            $table->text('google_map_embed_code')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('about_img')->nullable();
            $table->string('bin')->nullable();
            $table->string('app_link')->nullable();
            $table->longText('bill_footer')->nullable();
            $table->string('auth_bg')->nullable();
            $table->longText('footer_description')->nullable();
            $table->longText('bangla_footer_description')->nullable();
            // SEO TABLES
            $table->string('meta_title')->nullable();
            $table->string('og_image')->nullable();
            $table->string('gtag')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
