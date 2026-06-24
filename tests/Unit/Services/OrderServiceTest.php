<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderService::class);
    }

    public function test_create_order(): void
    {
        $data = [
            'name' => 'Иван Петров',
            'phone' => '+7 (999) 123-45-67',
            'message' => 'Тестовый заказ',
            'source' => 'catalog',
            'article_ids' => ['ART-001', 'ART-002'],
            'facade_top_color' => '#E8D5B7',
            'facade_bottom_color' => '#2C3E50',
        ];

        $order = $this->service->create($data);

        $this->assertNotNull($order);
        $this->assertEquals('new', $order->status);
        $this->assertEquals('catalog', $order->source);
        $this->assertEquals(['ART-001', 'ART-002'], $order->article_ids);
    }

    public function test_create_order_minimal_fields(): void
    {
        $data = [
            'phone' => '+7 (999) 111-22-33',
            'source' => 'callback',
        ];

        $order = $this->service->create($data);

        $this->assertNotNull($order);
        $this->assertEquals('callback', $order->source);
        $this->assertNull($order->name);
        $this->assertEmpty($order->article_ids);
    }
}
