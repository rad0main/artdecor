<?php

namespace Tests\Feature;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Models\CatalogImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_api_returns_paginated_results(): void
    {
        CatalogImage::factory(25)->create();

        $response = $this->getJson('/api/catalog?page=1');

        $response->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'last_page', 'total'])
            ->assertJsonCount(12, 'data');
    }

    public function test_catalog_api_filter_by_category(): void
    {
        $category = CatalogCategory::factory()->create();
        CatalogImage::factory(5)->create(['category_id' => $category->id]);
        CatalogImage::factory(5)->create();

        $response = $this->getJson("/api/catalog?category={$category->id}");

        $response->assertOk();
        foreach ($response->json('data') as $item) {
            $this->assertEquals($category->id, $item['category_id']);
        }
    }

    public function test_catalog_api_filter_by_color(): void
    {
        $color = CatalogColor::factory()->create();
        $image = CatalogImage::factory()->create();
        $image->colors()->attach($color);

        $response = $this->getJson("/api/catalog?color={$color->id}");

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    public function test_catalog_api_search_by_title(): void
    {
        CatalogImage::factory()->create(['title' => 'ART-12345']);
        CatalogImage::factory(3)->create();

        $response = $this->getJson('/api/catalog?search=12345');

        $response->assertOk();
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_categories_api(): void
    {
        CatalogCategory::factory(3)->create();

        $response = $this->getJson('/api/catalog/categories');

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function test_colors_api(): void
    {
        CatalogColor::factory(5)->create();

        $response = $this->getJson('/api/catalog/colors');

        $response->assertOk()
            ->assertJsonCount(5);
    }
}
