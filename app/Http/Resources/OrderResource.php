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
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
