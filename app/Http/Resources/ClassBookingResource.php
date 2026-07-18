<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassBookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fitness_class' => new FitnessClassResource($this->fitnessClass),
            'class_session_id' => $this->class_session_id,
            'booked_for_date' => $this->booked_for_date?->toDateString(),
            'booked_at' => $this->booked_at->toISOString(),
            'status' => $this->status,
            'access_type' => $this->access_type,
            'personal_trainer_requested' => $this->personal_trainer_requested,
            'amount' => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_reference' => $this->payment_reference,
            'payment_confirmations' => PaymentConfirmationResource::collection($this->whenLoaded('paymentConfirmations')),
        ];
    }
}
