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
        $activeMemberships = $member?->activeMemberships() ?? collect();

        $allowedClassTypes = [];
        $packageNames = [];
        foreach ($activeMemberships as $purchase) {
            if ($purchase->package) {
                $packageNames[] = $purchase->package->name;
                $allowed = $purchase->package->allowed_class_types ?? [];
                if (empty($allowed)) {
                    $allowedClassTypes = ['*'];
                    break;
                }
                $allowedClassTypes = array_unique(array_merge($allowedClassTypes, $allowed));
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?? '',
            'avatar_url' => $this->avatar_url,
            'member_code' => $member?->member_code,
            'membership_status' => $membership?->status ?? ($activeMemberships->isNotEmpty() ? 'active' : 'inactive'),
            'membership_expiry' => $membership?->expires_at?->toDateString(),
            'membership_package_name' => $membership?->package?->name ?? (! empty($packageNames) ? implode(', ', $packageNames) : null),
            'membership_includes_personal_trainer' => $activeMemberships->contains(fn ($p) => (bool) $p->includes_personal_trainer),
            'membership_remaining_visits' => $membership?->remainingVisits(),
            'membership_allowed_class_types' => array_values($allowedClassTypes),
        ];
    }
}
