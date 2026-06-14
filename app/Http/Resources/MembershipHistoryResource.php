<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'package_name' => $this->package->name,
            'price' => (float) $this->amount,
            'purchased_at' => $this->created_at->toISOString(),
            'status' => $this->status,
            'expired_at' => $this->expires_at?->toDateString() ?? now()->toDateString(),
            'includes_personal_trainer' => $this->includes_personal_trainer,
            'visits_allowed' => $this->visits_allowed,
            'visits_used' => $this->visits_used,
            'remaining_visits' => $this->remainingVisits(),
        ];
    }
}
