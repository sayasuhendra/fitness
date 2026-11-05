<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $class = $this->fitnessClass;

        return [
            'id' => $class?->id ?? 0,
            'session_id' => $this->id,
            'name' => $class?->name ?? 'Kelas',
            'description' => $class?->description ?? '',
            'class_type' => $class?->class_type ?? 'general',
            'trainer_name' => $this->trainer?->user?->name ?? $class?->trainer?->user?->name ?? 'Instruktur',
            'trainer_avatar_url' => $this->trainer?->user?->avatar_url ?? $class?->trainer?->user?->avatar_url,
            'capacity' => $this->capacity,
            'booked_count' => $this->confirmed_bookings_count ?? $this->confirmedBookingsCount(),
            'location' => $class?->location ?? 'Akhwat Gym Studio',
            'start_time' => (string) $this->start_time,
            'end_time' => (string) $this->end_time,
            'date' => $this->session_date->toDateString(),
            'is_recurring' => $class?->is_recurring ?? false,
            'recurring_days' => $class?->recurring_days ?? [],
            'recurrence_ends_at' => $class?->recurrence_ends_at?->toDateString(),
            'allow_drop_in' => $class?->allow_drop_in ?? false,
            'drop_in_price' => (float) ($class?->drop_in_price ?? 0),
            'trainer_addon_price' => (float) ($class?->trainer_addon_price ?? 0),
        ];
    }
}
