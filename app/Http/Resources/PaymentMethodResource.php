<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\BankAccount;
use App\Models\QrisPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof BankAccount) {
            return [
                'id' => $this->id,
                'type' => 'bank_transfer',
                'bank_name' => $this->bank_name,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
                'instructions' => $this->instructions,
            ];
        }

        /** @var QrisPaymentMethod $qris */
        $qris = $this->resource;

        return [
            'id' => $qris->id,
            'type' => 'qris',
            'name' => $qris->name,
            'image_url' => $qris->imageUrl(),
            'download_url' => $qris->imageUrl(),
            'instructions' => $qris->instructions,
        ];
    }
}
