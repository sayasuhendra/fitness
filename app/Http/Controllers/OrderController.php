<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Orders\CheckoutOrderAction;
use App\DTO\CheckoutData;
use App\Http\Requests\Orders\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->member->orders()
            ->with(['items.product.category', 'paymentConfirmations'])
            ->latest()
            ->get();

        return ApiResponder::success(OrderResource::collection($orders), 'Orders retrieved');
    }

    public function store(CheckoutRequest $request, CheckoutOrderAction $action): JsonResponse
    {
        $order = $action->execute($request->user()->member, new CheckoutData(
            items: $request->validated('items'),
            paymentMethod: $request->validated('payment_method'),
        ));

        return ApiResponder::success(new OrderResource($order->load('paymentConfirmations')), 'Order created. Please confirm your payment.', 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->member_id === $request->user()->member->id, 404);

        return ApiResponder::success(new OrderResource($order->load(['items.product.category', 'paymentConfirmations'])), 'Order retrieved');
    }

    public function reorder(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->member_id === $request->user()->member->id, 404);

        return ApiResponder::success(new OrderResource($order->load('items.product.category')), 'Previous order retrieved for reorder');
    }
}
