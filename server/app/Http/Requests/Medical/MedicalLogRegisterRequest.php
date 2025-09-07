<?php

namespace App\Http\Requests\Medical;
use App\Http\Requests\BaseRequest;

class MedicalLogRegisterRequest extends BaseRequest
{

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
            'reason_for_visit' => 'nullable|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'vet_name' => 'nullable|string|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'treatment_prescribed' => 'nullable|string|max:255',
            'attachment_url' => 'nullable|url|max:2048',
        ];
    }
}
