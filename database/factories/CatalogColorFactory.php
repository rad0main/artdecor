<?php

namespace Database\Factories;

use App\Models\CatalogColor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogColorFactory extends Factory
{
    protected $model = CatalogColor::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->word(),
            'name' => $this->faker->colorName(),
            'hex' => $this->faker->hexColor(),
            'sort_order' => 0,
        ];
    }
}
