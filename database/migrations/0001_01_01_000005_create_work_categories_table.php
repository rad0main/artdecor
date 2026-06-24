<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 255);
            $table->timestamps();
        });

        $categories = [
            ['slug' => 'vitrage', 'name' => 'Витраж'],
            ['slug' => 'mirror', 'name' => 'Зеркало'],
            ['slug' => 'kitchen-apron', 'name' => 'Кухонный фартук'],
            ['slug' => 'glass-panel', 'name' => 'Панно из стекла'],
            ['slug' => 'ceilings', 'name' => 'Потолки'],
            ['slug' => 'backlit-skinny', 'name' => 'Скинали с подсветкой'],
            ['slug' => 'backlit-panel', 'name' => 'Панно с подсветкой'],
            ['slug' => 'partitions', 'name' => 'Перегородки из стекла'],
            ['slug' => 'triplex', 'name' => 'Триплекс'],
            ['slug' => 'tile-panel', 'name' => 'Панно из плитки'],
        ];

        foreach ($categories as $cat) {
            DB::table('work_categories')->insert($cat);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('work_categories');
    }
};
