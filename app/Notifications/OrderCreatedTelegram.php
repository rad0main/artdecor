<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderCreatedTelegram extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram(object $notifiable): string
    {
        $sourceLabels = [
            'catalog' => 'Каталог',
            'primerka' => 'Примерка',
            'callback' => 'Звонок',
            'question' => 'Вопрос',
            'order' => 'Заявка',
        ];

        $source = $sourceLabels[$this->order->source] ?? $this->order->source;

        $message = "🆕 <b>Новый заказ #{$this->order->id}</b>\n";
        $message .= "─────────────────────\n";
        $message .= "👤 <b>Имя:</b> {$this->order->name}\n";
        $message .= "📞 <b>Телефон:</b> {$this->order->phone}\n";
        $message .= "📋 <b>Источник:</b> {$source}\n";

        if ($this->order->message) {
            $message .= "💬 <b>Сообщение:</b> {$this->order->message}\n";
        }

        if (! empty($this->order->article_ids)) {
            $articles = implode(', ', $this->order->article_ids);
            $message .= "🏷️ <b>Артикулы:</b> {$articles}\n";
        }

        if ($this->order->facade_top_color) {
            $message .= "🎨 <b>Цвет верха:</b> {$this->order->facade_top_color}\n";
        }

        if ($this->order->facade_bottom_color) {
            $message .= "🎨 <b>Цвет низа:</b> {$this->order->facade_bottom_color}\n";
        }

        $message .= "🕐 <b>Дата:</b> {$this->order->created_at->format('d.m.Y H:i')}\n";

        return $message;
    }
}
