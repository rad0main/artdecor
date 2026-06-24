<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function create(array $data): Order
    {
        return Order::create([
            'name' => $data['name'] ?? null,
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'source' => $data['source'],
            'article_ids' => $data['article_ids'] ?? [],
            'facade_top_color' => $data['facade_top_color'] ?? null,
            'facade_bottom_color' => $data['facade_bottom_color'] ?? null,
            'status' => 'new',
        ]);
    }
}
