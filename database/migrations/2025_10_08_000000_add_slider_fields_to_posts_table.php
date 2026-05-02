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
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_slider')->default(false)->after('is_published');
            $table->foreignId('slider_gallery_id')->nullable()->constrained('galleries')->onDelete('set null')->after('gallery_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['slider_gallery_id']);
            $table->dropColumn('slider_gallery_id');
            $table->dropColumn('is_slider');
        });
    }
};
