<?php

namespace Database\Factories;

use App\Models\CatalogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogImageFactory extends Factory
{
    protected $model = \App\Models\CatalogImage::class;

    public function definition(): array
    {
        return [
            'title' => 'ART-' . $this->faker->unique()->numberBetween(1000, 9999),
            'category_id' => CatalogCategory::factory(),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
