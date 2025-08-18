<?php

namespace App\Http\Requests\Vet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // The authorization logic is better handled in the controller to use the
        // existing appointment instance to check the pet's owner.
        // Returning true here allows the controller to handle authorization.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // The 'sometimes' rule ensures the field is only validated if it's present in the request.
            'pet_id' => ['sometimes', 'integer', 'exists:pets,id'],
            'vet_id' => ['sometimes', 'integer', 'exists:vets,id'],
            'app_date' => ['sometimes', 'date', 'after_or_equal:today'],
            'app_time' => ['sometimes', 'date_format:H:i'],
            'visit_reason' => ['sometimes', 'string', 'max:1000'],
            'status' => ['sometimes', 'string', 'in:Scheduled,Completed,Canceled'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pet_id.exists' => 'The selected pet does not exist.',
            'vet_id.exists' => 'The selected veterinarian does not exist.',
            'app_date.after_or_equal' => 'The appointment date must be today or a future date.',
            'app_time.date_format' => 'The appointment time must be in HH:MM format.',
            'status.in' => 'The status must be one of the following: Scheduled, Completed, Canceled.',
        ];
    }
}
