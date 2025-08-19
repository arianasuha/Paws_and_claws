<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserReminderRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, we assume authorization is handled by middleware
        // on the route. In a real application, you might add more granular
        // checks here (e.g., does the authenticated user have permission to
        // create a reminder for this user).
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
            'reminder_id' => ['required', 'integer', 'exists:reminders,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
