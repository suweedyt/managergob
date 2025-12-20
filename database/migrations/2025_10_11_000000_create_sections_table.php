<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title_full');
            $table->string('title_short')->nullable();
            $table->string('logo_class')->nullable();
            $table->string('logo_image')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('mode', ['content', 'link'])->default('content');
            $table->string('redirect_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
}
