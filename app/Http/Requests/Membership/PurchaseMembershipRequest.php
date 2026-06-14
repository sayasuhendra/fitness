<?php

declare(strict_types=1);

namespace App\Http\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id' => ['required', 'integer', Rule::exists('membership_packages', 'id')],
            'payment_method' => ['required', 'string', Rule::in(['qris', 'bank_transfer', 'midtrans', 'cash'])],
            'billing_cycle' => ['nullable', 'string', Rule::in(['monthly', 'yearly', 'one_time'])],
        ];
    }
}
