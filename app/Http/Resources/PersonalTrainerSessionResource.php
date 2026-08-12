<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalTrainerSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $trainer = $this->personalTrainer ?? $this->trainer;
        $whatsappNumber = $trainer?->whatsapp_number ?: $trainer?->user?->phone;
        $normalizedWhatsappNumber = $whatsappNumber === null
            ? null
            : preg_replace('/\D+/', '', $whatsappNumber);

        return [
            'id' => $this->id,
            'trainer_name' => $trainer?->user?->name,
            'trainer_whatsapp_number' => $normalizedWhatsappNumber,
            'trainer_whatsapp_url' => $normalizedWhatsappNumber ? 'https://wa.me/'.$normalizedWhatsappNumber : null,
            'scheduled_at' => $this->scheduled_at->toISOString(),
            'duration_minutes' => $this->duration_minutes,
            'status' => $this->status,
            'access_type' => $this->access_type,
            'amount' => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'completed_at' => $this->completed_at?->toISOString(),
            'member_note' => $this->member_note,
            'admin_note' => $this->admin_note,
            'payment_confirmations' => PaymentConfirmationResource::collection($this->whenLoaded('paymentConfirmations')),
        ];
    }
}
