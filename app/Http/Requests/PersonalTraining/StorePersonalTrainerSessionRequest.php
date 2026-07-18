<?php

declare(strict_types=1);

namespace App\Http\Requests\PersonalTraining;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonalTrainerSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trainer_id' => ['required', 'integer', Rule::exists('trainers', 'id')],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['nullable', 'integer', 'min:30', 'max:180'],
            'access_type' => ['required', 'string', Rule::in(['membership', 'one_time'])],
            'payment_method' => ['nullable', 'required_if:access_type,one_time', 'string', Rule::in(['qris', 'bank_transfer', 'cash'])],
            'member_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
