<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentConfirmationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payable_type' => class_basename($this->payable_type),
            'payable_id' => $this->payable_id,
            'payment_method' => $this->payment_method,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'proof_url' => $this->proofUrl(),
            'whatsapp_url' => $this->whatsapp_url,
            'member_note' => $this->member_note,
            'admin_note' => $this->admin_note,
            'verified_at' => $this->verified_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
