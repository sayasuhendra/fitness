<?php

declare(strict_types=1);

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFcmTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => ['required', 'string', 'max:4096'],
            'platform' => ['nullable', 'string', Rule::in(['android', 'ios', 'web'])],
        ];
    }
}
