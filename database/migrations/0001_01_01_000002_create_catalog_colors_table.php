<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_colors', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 50);
            $table->string('hex', 7);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        $colors = [
            ['slug' => 'red', 'name' => 'Красный', 'hex' => '#E74C3C', 'sort_order' => 1],
            ['slug' => 'orange', 'name' => 'Оранжевый', 'hex' => '#F39C12', 'sort_order' => 2],
            ['slug' => 'yellow', 'name' => 'Жёлтый', 'hex' => '#F1C40F', 'sort_order' => 3],
            ['slug' => 'green', 'name' => 'Зелёный', 'hex' => '#2ECC71', 'sort_order' => 4],
            ['slug' => 'blue', 'name' => 'Синий', 'hex' => '#3498DB', 'sort_order' => 5],
            ['slug' => 'darkblue', 'name' => 'Тёмно-синий', 'hex' => '#2C3E50', 'sort_order' => 6],
            ['slug' => 'purple', 'name' => 'Фиолетовый', 'hex' => '#9B59B6', 'sort_order' => 7],
            ['slug' => 'pink', 'name' => 'Розовый', 'hex' => '#E91E63', 'sort_order' => 8],
            ['slug' => 'brown', 'name' => 'Коричневый', 'hex' => '#795548', 'sort_order' => 9],
            ['slug' => 'beige', 'name' => 'Бежевый', 'hex' => '#D4C5A9', 'sort_order' => 10],
            ['slug' => 'white', 'name' => 'Белый', 'hex' => '#F5F5F5', 'sort_order' => 11],
            ['slug' => 'gray', 'name' => 'Серый', 'hex' => '#95A5A6', 'sort_order' => 12],
            ['slug' => 'black', 'name' => 'Чёрный', 'hex' => '#2C3E50', 'sort_order' => 13],
        ];

        foreach ($colors as $color) {
            DB::table('catalog_colors')->insert($color);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_colors');
    }
};
