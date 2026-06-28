<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Notifications\OrderCreatedTelegram;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    public function create(array $data): Order
    {
        $order = Order::create([
            'name' => $data['name'] ?? null,
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'source' => $data['source'],
            'article_ids' => $data['article_ids'] ?? [],
            'facade_top_color' => $data['facade_top_color'] ?? null,
            'facade_bottom_color' => $data['facade_bottom_color'] ?? null,
            'status' => 'new',
        ]);

        // Send Telegram notification
        Notification::route('telegram', '')
            ->notify(new OrderCreatedTelegram($order));

        return $order;
    }
}
