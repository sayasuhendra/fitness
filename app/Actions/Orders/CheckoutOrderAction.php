<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\DTO\CheckoutData;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Notifications\MemberNotificationService;
use App\Support\AdminShift;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class CheckoutOrderAction
{
    public function execute(Member $member, CheckoutData $data, ?User $admin = null, bool $markAsPaid = false): Order
    {
        $order = DB::transaction(function () use ($member, $data, $admin, $markAsPaid): Order {
            $order = Order::query()->create([
                'member_id' => $member->id,
                ...AdminShift::stamp($admin),
                'status' => $markAsPaid ? 'paid' : 'pending_payment',
                'payment_method' => $data->paymentMethod,
                'payment_reference' => 'MANUAL-'.Str::upper(Str::random(12)),
                'total_price' => 0,
            ]);

            $total = 0.0;

            foreach ($data->items as $item) {
                /** @var Product $product */
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if (! $product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => "{$product->name} sudah tidak tersedia.",
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup.",
                    ]);
                }

                $unitPrice = (float) $product->price;
                $unitCost = (float) $product->cost_price;
                $subtotal = $unitPrice * $quantity;
                $subtotalCost = $unitCost * $quantity;
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                    'subtotal_cost' => $subtotalCost,
                    'profit_amount' => $subtotal - $subtotalCost,
                ]);

                $product->decrement('stock', $quantity);
                $total += $subtotal;
            }

            $order->update(['total_price' => $total]);

            return $order->load('items.product.category');
        });

        if ($markAsPaid) {
            app(MemberNotificationService::class)->send(
                $member->user,
                'Pesanan sudah dibayar',
                'Pesanan produk Anda sudah tercatat lunas di Akhwat Gym.',
                'order_paid',
                '/orders/'.$order->id,
            );
        } else {
            app(MemberNotificationService::class)->send(
                $member->user,
                'Pesanan menunggu pembayaran',
                'Pesanan Anda sudah dibuat. Silakan konfirmasi pembayaran agar admin dapat memproses pesanan.',
                'order_pending_payment',
                '/payments/manual?payable_type=order&payable_id='.$order->id.'&amount='.$order->total_price.'&payment_method='.$order->payment_method,
            );
        }

        return $order;
    }
}
