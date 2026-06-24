<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 255);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed predefined categories
        $categories = [
            ['slug' => 'abstr', 'name' => 'Абстракция', 'sort_order' => 1],
            ['slug' => 'animals', 'name' => 'Животные', 'sort_order' => 2],
            ['slug' => 'architecture', 'name' => 'Архитектура', 'sort_order' => 3],
            ['slug' => 'cities', 'name' => 'Города', 'sort_order' => 4],
            ['slug' => 'design', 'name' => 'Дизайн', 'sort_order' => 5],
            ['slug' => 'food', 'name' => 'Еда', 'sort_order' => 6],
            ['slug' => 'flowers', 'name' => 'Цветы', 'sort_order' => 7],
            ['slug' => 'nature', 'name' => 'Природа', 'sort_order' => 8],
            ['slug' => 'sea', 'name' => 'Море', 'sort_order' => 9],
            ['slug' => 'textures', 'name' => 'Текстуры', 'sort_order' => 10],
            ['slug' => 'waterfalls', 'name' => 'Водопады', 'sort_order' => 11],
        ];

        foreach ($categories as $category) {
            DB::table('catalog_categories')->insert($category);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_categories');
    }
};
