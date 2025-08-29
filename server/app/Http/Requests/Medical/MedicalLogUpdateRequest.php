<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class MedicalLogUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'pet_id' => 'required|integer|exists:pets,id',
            'visit_date' => 'required|date',
            'visit_reason' => 'nullable|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'vet_name' => 'nullable|string|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'prescribed_medication' => 'nullable|string|max:255',
            'attachment_url' => 'sometimes|string|max:2048',
        ];
    }
}