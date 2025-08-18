<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class PetMedicalRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // By default, we'll allow all requests. You may want to add
        // authentication/authorization logic here to ensure only
        // permitted users can create new records.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // The pet_id must be present, an integer, and must exist in the 'pets' table.
            'pet_id' => ['required', 'integer', 'exists:pets,id'],

            // The medical_id must be present, an integer, and must exist in the 'medical_logs' table.
            'medical_id' => ['required', 'integer', 'exists:medical_logs,id'],
        ];
    }
}
