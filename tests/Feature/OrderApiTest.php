<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_success(): void
    {
        $response = $this->postJson('/api/order', [
            'name' => 'Иван',
            'phone' => '+7 (999) 123-45-67',
            'message' => 'Хочу заказать скинали',
            'source' => 'catalog',
            'article_ids' => ['ART-001'],
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    public function test_create_order_without_name(): void
    {
        $response = $this->postJson('/api/order', [
            'phone' => '+7 (999) 123-45-67',
            'source' => 'primerka',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_create_order_invalid_phone(): void
    {
        $response = $this->postJson('/api/order', [
            'name' => 'Иван',
            'phone' => 'not-a-phone',
            'source' => 'callback',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_create_order_invalid_source(): void
    {
        $response = $this->postJson('/api/order', [
            'name' => 'Иван',
            'phone' => '+7 (999) 123-45-67',
            'source' => 'invalid_source',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source']);
    }
}
