<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Mail\OrderCreatedMail;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->validated());

        Mail::to(config('mail.admin_address'))
            ->queue(new OrderCreatedMail($order));

        return response()->json([
            'success' => true,
            'message' => 'Спасибо! Заказ принят. Мы перезвоним вам в течение 2 часов.',
        ]);
    }
}
