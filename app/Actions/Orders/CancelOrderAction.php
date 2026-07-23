<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CancelOrderAction
{
    public function execute(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $order = Order::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($order->status === 'cancelled') {
                return $order;
            }

            if ($order->status === 'completed') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan yang sudah selesai tidak bisa dibatalkan dari halaman ini.',
                ]);
            }

            foreach ($order->items as $item) {
                Product::query()
                    ->whereKey($item->product_id)
                    ->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            return $order->refresh();
        });
    }
}
