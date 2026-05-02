<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'footer_background_color')) {
                $table->string('footer_background_color', 7)->default('#101010')->after('header_logo');
            }

            if (!Schema::hasColumn('site_settings', 'footer_contact')) {
                $table->text('footer_contact')->nullable()->after('footer_background_color');
            }

            if (!Schema::hasColumn('site_settings', 'footer_socials')) {
                $table->json('footer_socials')->nullable()->after('footer_contact');
            }

            if (!Schema::hasColumn('site_settings', 'footer_copy')) {
                $table->string('footer_copy')->nullable()->after('footer_socials');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (Schema::hasColumn('site_settings', 'footer_copy')) {
                $table->dropColumn('footer_copy');
            }

            if (Schema::hasColumn('site_settings', 'footer_socials')) {
                $table->dropColumn('footer_socials');
            }

            if (Schema::hasColumn('site_settings', 'footer_contact')) {
                $table->dropColumn('footer_contact');
            }

            if (Schema::hasColumn('site_settings', 'footer_background_color')) {
                $table->dropColumn('footer_background_color');
            }
        });
    }
};
