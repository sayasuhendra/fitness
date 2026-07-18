<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => $this->items->map(fn ($item): array => [
                'product' => new ProductResource($item->product),
                'quantity' => $item->quantity,
            ])->values(),
            'total_price' => (float) $this->total_price,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'payment_confirmations' => PaymentConfirmationResource::collection($this->whenLoaded('paymentConfirmations')),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
