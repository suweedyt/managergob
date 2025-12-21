<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'footer_map_iframe')) {
                $table->text('footer_map_iframe')->nullable();
            }

            if (!Schema::hasColumn('site_settings', 'footer_socials')) {
                $table->json('footer_socials')->nullable();
            }

            if (!Schema::hasColumn('site_settings', 'footer_links')) {
                $table->json('footer_links')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'footer_links')) {
                $table->dropColumn('footer_links');
            }
            if (Schema::hasColumn('site_settings', 'footer_socials')) {
                $table->dropColumn('footer_socials');
            }
            if (Schema::hasColumn('site_settings', 'footer_map_iframe')) {
                $table->dropColumn('footer_map_iframe');
            }
        });
    }
};
