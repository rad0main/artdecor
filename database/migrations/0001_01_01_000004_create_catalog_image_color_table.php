<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_image_color', function (Blueprint $table) {
            $table->foreignId('image_id')->constrained('catalog_images')->cascadeOnDelete();
            $table->foreignId('color_id')->constrained('catalog_colors')->cascadeOnDelete();
            $table->primary(['image_id', 'color_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_image_color');
    }
};
