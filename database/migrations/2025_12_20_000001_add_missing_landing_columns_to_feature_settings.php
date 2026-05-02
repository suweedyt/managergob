<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('feature_settings', 'link_type')) {
                $table->string('link_type', 20)->default('url');
            }
            if (!Schema::hasColumn('feature_settings', 'button_url')) {
                $table->string('button_url')->nullable();
            }
            if (!Schema::hasColumn('feature_settings', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('feature_settings', 'landing_content')) {
                $table->longText('landing_content')->nullable();
            }
            if (!Schema::hasColumn('feature_settings', 'landing_image')) {
                $table->string('landing_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('feature_settings', function (Blueprint $table) {
            if (Schema::hasColumn('feature_settings', 'link_type')) {
                $table->dropColumn('link_type');
            }
            if (Schema::hasColumn('feature_settings', 'button_url')) {
                $table->dropColumn('button_url');
            }
            if (Schema::hasColumn('feature_settings', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('feature_settings', 'landing_content')) {
                $table->dropColumn('landing_content');
            }
            if (Schema::hasColumn('feature_settings', 'landing_image')) {
                $table->dropColumn('landing_image');
            }
        });
    }
};
