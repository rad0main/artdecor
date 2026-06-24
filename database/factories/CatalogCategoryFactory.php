<?php

namespace Database\Factories;

use App\Models\CatalogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogCategoryFactory extends Factory
{
    protected $model = CatalogCategory::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->word(),
            'name' => $this->faker->word(),
            'sort_order' => 0,
        ];
    }
}
