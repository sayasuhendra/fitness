<?php

declare(strict_types=1);

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', Rule::exists('fitness_classes', 'id')],
            'booked_for_date' => ['nullable', 'date'],
            'access_type' => ['nullable', 'string', Rule::in(['membership', 'one_time'])],
            'personal_trainer_requested' => ['nullable', 'boolean'],
            'payment_method' => ['nullable', 'string', Rule::in(['qris', 'bank_transfer', 'midtrans', 'cash'])],
        ];
    }
}
