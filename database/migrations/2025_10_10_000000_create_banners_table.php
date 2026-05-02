<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_description', 180)->nullable();
            $table->text('long_description')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_bg_color', 20)->default('#0069d9');
            $table->string('button_url')->nullable();
            $table->string('media_path');
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->unsignedTinyInteger('position_x')->nullable();
            $table->unsignedTinyInteger('position_y')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
}
