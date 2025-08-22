<?php

namespace App\Http\Requests\Medical;

use Illuminate\Foundation\Http\FormRequest;

class DiseaseLogUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        // Set to true to allow the request to be processed.
        // You might add logic here to check if the authenticated user has
        // permission to update this specific disease log.
        return true;
    }


    public function rules(): array
    {
        return [
            // All fields are optional since it's an update, but they must
            // follow the same type and length rules if provided.
            'symptoms' => ['sometimes', 'string', 'max:5000'],
            'causes' => ['sometimes', 'string', 'max:5000'],
            'treat_options' => ['sometimes', 'string', 'max:5000'],
            'severity' => ['sometimes', 'string', 'max:255'],

            // Assuming a pet_ids array is submitted to update the relationship.
            'pet_ids' => ['sometimes', 'array'],
            'pet_ids.*' => ['integer', 'exists:pets,id'],
        ];
    }
}
