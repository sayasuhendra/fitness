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
            'booked_at' => $this->booked_at->toISOString(),
            'status' => $this->status,
        ];
    }
}
