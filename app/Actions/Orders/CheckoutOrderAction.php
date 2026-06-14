<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\DTO\CheckoutData;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class CheckoutOrderAction
{
    public function execute(Member $member, CheckoutData $data): Order
    {
        return DB::transaction(function () use ($member, $data): Order {
            $order = Order::query()->create([
                'member_id' => $member->id,
                'status' => 'completed',
                'payment_method' => $data->paymentMethod,
                'payment_reference' => 'MID-'.Str::upper(Str::random(12)),
                'total_price' => 0,
            ]);

            $total = 0.0;

            foreach ($data->items as $item) {
                /** @var Product $product */
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} stock is not sufficient.",
                    ]);
                }

                $subtotal = (float) $product->price * $quantity;
                $product->decrement('stock', $quantity);
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $order->update(['total_price' => $total]);

            return $order->load('items.product.category');
        });
    }
}
