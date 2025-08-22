<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class PetMedicalUpdateRequest extends FormRequest
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
        // permitted users can update records.
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
            // The fields are 'sometimes' required for an update, meaning they will
            // be validated only if they are present in the request.
            'pet_id' => ['sometimes', 'integer', 'exists:pets,id'],
            'medical_id' => ['sometimes', 'integer', 'exists:medical_logs,id'],
        ];
    }
}
