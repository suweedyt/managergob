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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('header_height')->default(80);
            $table->string('header_background_color', 7)->default('#ffffff');
            $table->string('header_logo')->nullable();
            $table->string('footer_background_color', 7)->default('#101010');
            $table->text('footer_contact')->nullable();
            $table->json('footer_socials')->nullable();
            $table->string('footer_copy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
