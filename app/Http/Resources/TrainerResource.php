<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'phone' => $this->user?->phone,
            'avatar_url' => $this->user?->avatar_url,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'is_active' => $this->is_active,
        ];
    }
}
