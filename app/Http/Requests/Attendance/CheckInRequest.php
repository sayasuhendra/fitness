<?php

declare(strict_types=1);

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qr_payload' => ['nullable', 'string'],
            'member_id' => ['nullable', 'integer', Rule::exists('members', 'id')],
            'class_booking_id' => ['nullable', 'integer', Rule::exists('class_bookings', 'id')],
            'personal_trainer_session_id' => ['nullable', 'integer', Rule::exists('personal_trainer_sessions', 'id')],
            'attendance_type' => ['required', 'string', Rule::in(['gym_visit', 'class_attendance', 'personal_trainer_session'])],
            'location' => ['nullable', 'string', 'max:160'],
        ];
    }
}
