<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLargeToNewsShowcaseItems extends Migration
{
    public function up()
    {
        Schema::table('news_showcase_items', function (Blueprint $table) {
            $table->boolean('is_large')->default(false)->after('post_id');
        });
    }

    public function down()
    {
        Schema::table('news_showcase_items', function (Blueprint $table) {
            $table->dropColumn('is_large');
        });
    }
}