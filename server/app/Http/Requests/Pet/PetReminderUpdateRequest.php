<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class PetReminderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Authorization is handled by the 'auth:sanctum' middleware on the route
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'pet_id' => ['sometimes', 'required', 'integer', 'exists:pets,id'],
            'med_name' => ['sometimes', 'required', 'string', 'max:255'],
            'dosage' => ['sometimes', 'required', 'string', 'max:255'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'reminder_time' => ['sometimes', 'required', 'date_format:H:i:s'],
        ];
    }
}
