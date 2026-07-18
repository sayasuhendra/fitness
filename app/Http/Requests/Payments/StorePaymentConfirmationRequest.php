<?php

declare(strict_types=1);

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentConfirmationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payable_type' => ['required', 'string', Rule::in(['membership_purchase', 'order', 'class_booking', 'personal_trainer_session'])],
            'payable_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', Rule::in(['qris', 'bank_transfer', 'cash'])],
            'proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'member_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
