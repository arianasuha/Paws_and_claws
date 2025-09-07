<?php

namespace App\Http\Requests\Medical;

use App\Http\Requests\BaseRequest;

class MedicalLogUpdateRequest extends BaseRequest
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
            'visit_date' => 'sometimes|date',
            'reason_for_visit' => 'sometimes|string|max:255',
            'diagnosis' => 'sometimes|string|max:255',
            'notes' => 'sometimes|string',
            'vet_name' => 'sometimes|string|max:255',
            'clinic_name' => 'sometimes|string|max:255',
            'treatment_prescribed' => 'sometimes|string|max:255',
            'attachment_url' => 'sometimes|string|max:2048',
        ];
    }
}