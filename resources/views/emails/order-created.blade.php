<x-mail::message>
# Новый заказ #{{ $order->id }}

Поступил новый заказ с сайта **ArtDecor**.

---

## Данные клиента

- **Имя:** {{ $order->name ?? 'Не указано' }}
- **Телефон:** {{ $order->phone }}
- **Сообщение:** {{ $order->message ?? 'Нет' }}

## Детали заказа

- **Источник:** 
  @switch($order->source)
      @case('catalog') Каталог @break
      @case('primerka') Примерка @break
      @case('callback') Заказ звонка @break
      @case('question') Вопрос @break
      @default {{ $order->source }}
  @endswitch
- **Артикулы:** {{ is_array($order->article_ids) ? implode(', ', $order->article_ids) : $order->article_ids ?? 'Нет' }}
- **Цвет верха:** {{ $order->facade_top_color ?? 'Не выбран' }}
- **Цвет низа:** {{ $order->facade_bottom_color ?? 'Не выбран' }}
- **Статус:** 
  @switch($order->status)
      @case('new') Новый @break
      @case('contacted') Обработан @break
      @case('closed') Закрыт @break
      @default {{ $order->status }}
  @endswitch

<x-mail::button :url="url('/admin/orders/' . $order->id . '/edit')">
    Открыть в админке
</x-mail::button>

С уважением,<br>
ArtDecor
</x-mail::message>
