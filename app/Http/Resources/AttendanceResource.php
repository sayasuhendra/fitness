<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attendance_type' => $this->attendance_type,
            'check_in_time' => $this->check_in_time->toISOString(),
            'status' => $this->status,
            'class_name' => $this->fitnessClass?->name,
            'location' => $this->location,
        ];
    }
}
