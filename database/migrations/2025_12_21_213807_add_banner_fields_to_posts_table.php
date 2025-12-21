<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add columns only if they don't exist to be safe on existing environments
        if (! Schema::hasColumn('posts', 'banner_show_caption')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->boolean('banner_show_caption')->default(true)->after('banner_short_description');
            });
        }

        if (! Schema::hasColumn('posts', 'banner_button_text')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('banner_button_text', 80)->nullable()->after('banner_show_caption');
            });
        }

        if (! Schema::hasColumn('posts', 'banner_button_bg_color')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('banner_button_bg_color', 20)->nullable()->after('banner_button_text');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop columns if they exist
        if (Schema::hasColumn('posts', 'banner_button_bg_color')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('banner_button_bg_color');
            });
        }

        if (Schema::hasColumn('posts', 'banner_button_text')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('banner_button_text');
            });
        }

        if (Schema::hasColumn('posts', 'banner_show_caption')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('banner_show_caption');
            });
        }
    }
};
