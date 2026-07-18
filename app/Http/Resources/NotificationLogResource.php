<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'title' => $this->data['title'] ?? 'Notification',
            'body' => $this->data['body'] ?? '',
            'type' => $this->data['type'] ?? null,
            'action_url' => $this->data['action_url'] ?? null,
            'created_at' => $this->created_at?->toISOString() ?? now()->toISOString(),
            'is_read' => $this->read_at !== null,
        ];
    }
}
