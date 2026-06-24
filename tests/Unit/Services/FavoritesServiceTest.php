<?php

namespace Tests\Unit\Services;

use App\Models\CatalogImage;
use App\Models\Favorite;
use App\Services\FavoritesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesServiceTest extends TestCase
{
    use RefreshDatabase;

    private FavoritesService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FavoritesService::class);
    }

    public function test_add_favorite(): void
    {
        $image = CatalogImage::factory()->create();
        $sessionId = 'test_session_123';

        $result = $this->service->toggle($image->id, $sessionId);

        $this->assertEquals(['action' => 'added'], $result);
        $this->assertDatabaseHas('favorites', [
            'session_id' => $sessionId,
            'image_id' => $image->id,
        ]);
    }

    public function test_remove_favorite(): void
    {
        $image = CatalogImage::factory()->create();
        $sessionId = 'test_session_123';

        Favorite::create([
            'session_id' => $sessionId,
            'image_id' => $image->id,
        ]);

        $result = $this->service->toggle($image->id, $sessionId);

        $this->assertEquals(['action' => 'removed'], $result);
        $this->assertDatabaseMissing('favorites', [
            'session_id' => $sessionId,
            'image_id' => $image->id,
        ]);
    }

    public function test_get_favorites(): void
    {
        $image = CatalogImage::factory()->create();
        $sessionId = 'test_session_456';

        Favorite::create([
            'session_id' => $sessionId,
            'image_id' => $image->id,
        ]);

        $favorites = $this->service->getFavorites($sessionId);

        $this->assertCount(1, $favorites);
        $this->assertEquals($image->id, $favorites->first()['id']);
    }

    public function test_get_favorites_empty(): void
    {
        $favorites = $this->service->getFavorites('nonexistent_session');
        $this->assertCount(0, $favorites);
    }
}
