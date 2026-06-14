<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipPackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'original_price' => $this->original_price !== null ? (float) $this->original_price : null,
            'discount_percent' => $this->discount_percent,
            'duration_days' => $this->duration_days,
            'package_type' => $this->package_type,
            'billing_cycle' => $this->billing_cycle,
            'includes_personal_trainer' => $this->includes_personal_trainer,
            'has_visit_limit' => $this->has_visit_limit,
            'visit_limit' => $this->visit_limit,
            'allowed_class_types' => $this->allowed_class_types ?? [],
            'visit_label' => $this->visitLabel(),
        ];
    }
}
