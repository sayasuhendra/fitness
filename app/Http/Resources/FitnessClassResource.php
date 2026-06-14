<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FitnessClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'trainer_name' => $this->trainer->user->name,
            'trainer_avatar_url' => $this->trainer->user->avatar_url,
            'capacity' => $this->capacity,
            'booked_count' => $this->confirmed_bookings_count ?? $this->confirmedBookingsCount(),
            'location' => $this->location,
            'start_time' => (string) $this->start_time,
            'end_time' => (string) $this->end_time,
            'date' => $this->class_date->toDateString(),
        ];
    }
}
