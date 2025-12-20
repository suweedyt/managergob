<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_settings', function (Blueprint $table) {
            $table->string('link_type', 20)->default('url');
            $table->string('button_url')->nullable();
            $table->string('slug')->nullable();
            $table->longText('landing_content')->nullable();
            $table->string('landing_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('feature_settings', function (Blueprint $table) {
            $table->dropColumn(['link_type', 'button_url', 'slug', 'landing_content', 'landing_image']);
        });
    }
};
