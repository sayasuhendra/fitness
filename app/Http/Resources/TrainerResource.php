<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $whatsappNumber = $this->whatsapp_number ?: $this->user?->phone;
        $normalizedWhatsappNumber = $whatsappNumber === null
            ? null
            : preg_replace('/\D+/', '', $whatsappNumber);

        return [
            'id' => $this->id,
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'phone' => $normalizedWhatsappNumber,
            'whatsapp_number' => $normalizedWhatsappNumber,
            'whatsapp_url' => $normalizedWhatsappNumber ? 'https://wa.me/'.$normalizedWhatsappNumber : null,
            'avatar_url' => $this->user?->avatar_url,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'is_active' => $this->is_active,
        ];
    }
}
