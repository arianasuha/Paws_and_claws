<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserReminderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, we assume authorization is handled by middleware.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // The 'sometimes' rule allows for updating only one field.
            'reminder_id' => ['sometimes', 'integer', 'exists:reminders,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }
}
