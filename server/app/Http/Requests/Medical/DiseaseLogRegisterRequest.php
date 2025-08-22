<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class DiseaseLogRegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        // Set this to true to allow the request to be processed.
        // You might add logic here to check if the authenticated user has
        // permission to create a disease log.
        return true;
    }

    public function rules(): array
    {
        return [
            // The 'symptoms' field must be a required string.
            // The max length is set to a high value to accommodate longer text.
            'symptoms' => ['required', 'string', 'max:5000'],

            // The 'causes' field must be a required string.
            'causes' => ['required', 'string', 'max:5000'],

            // The 'treat_options' field must be a required string.
            'treat_options' => ['required', 'string', 'max:5000'],

            // The 'severity' field must be a required string.
            // A good practice would be to define a set of allowed values, e.g.,
            // 'severity' => ['required', 'string', 'in:mild,moderate,severe'],
            'severity' => ['required', 'string', 'max:255'],

            // Assuming a pet_id is submitted to link the disease log.
            // It should be an array of integers that correspond to existing 'pets' IDs.
            'pet_ids' => ['sometimes', 'array'],
            'pet_ids.*' => ['integer', 'exists:pets,id'],
        ];
    }
}
