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
            'description' => $this->description,
            'class_type' => $this->class_type,
            'trainer_name' => $this->trainer->user->name,
            'trainer_avatar_url' => $this->trainer->user->avatar_url,
            'capacity' => $this->capacity,
            'booked_count' => $this->confirmed_bookings_count_for_date
                ?? $this->confirmed_bookings_count
                ?? $this->confirmedBookingsCount(),
            'location' => $this->location,
            'start_time' => (string) $this->start_time,
            'end_time' => (string) $this->end_time,
            'date' => $this->class_date->toDateString(),
            'is_recurring' => $this->is_recurring,
            'recurring_days' => $this->recurring_days ?? [],
            'recurrence_ends_at' => $this->recurrence_ends_at?->toDateString(),
            'allow_drop_in' => $this->allow_drop_in,
            'drop_in_price' => (float) $this->drop_in_price,
            'trainer_addon_price' => (float) $this->trainer_addon_price,
        ];
    }
}
