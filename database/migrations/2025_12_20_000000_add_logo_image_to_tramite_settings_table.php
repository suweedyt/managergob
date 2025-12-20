<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('tramite_settings', 'logo_image')) {
            Schema::table('tramite_settings', function (Blueprint $table) {
                $table->string('logo_image')->nullable()->after('subtitle');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('tramite_settings', 'logo_image')) {
            Schema::table('tramite_settings', function (Blueprint $table) {
                $table->dropColumn('logo_image');
            });
        }
    }
};
