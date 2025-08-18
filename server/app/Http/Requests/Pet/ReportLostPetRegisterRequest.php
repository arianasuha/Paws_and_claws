<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class ReportLostPetRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming the user must be authenticated to create a lost pet report.
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
            'location' => ['required', 'string', 'max:255'],
            'date_lost' => ['required', 'date'],
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'status' => ['required', 'string', 'in:Lost,Found'],
        ];
    }
}
