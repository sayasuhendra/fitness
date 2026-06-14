<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $member = $this->member;
        $membership = $member?->activeMembership();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? '',
            'avatar_url' => $this->avatar_url,
            'membership_status' => $membership?->status ?? 'inactive',
            'membership_expiry' => $membership?->expires_at?->toDateString(),
        ];
    }
}
