<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportLostPetUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming the user must be the owner of the report to update it.
        // The controller will handle the specific authorization check using a policy.
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'location' => ['sometimes', 'string', 'max:255'],
            'date_lost' => ['sometimes', 'date'],
            'pet_id' => ['sometimes', 'integer', 'exists:pets,id'],
            'status' => ['sometimes', 'string', 'in:Lost,Found'],
        ];
    }
}
