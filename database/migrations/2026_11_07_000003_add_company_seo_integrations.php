<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('google_site_verification')->nullable();
            $table->boolean('analytics_enabled')->default(false);
            $table->boolean('search_indexing_enabled')->default(true);
        });
    }
    public function down(): void
    {
        Schema::table('company_settings', fn (Blueprint $table) => $table->dropColumn(['google_site_verification', 'analytics_enabled', 'search_indexing_enabled']));
    }
};
