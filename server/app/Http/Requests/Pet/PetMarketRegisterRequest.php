<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;

class PetMarketRegisterRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'pet_id' => 'required|integer|exists:pets,id',
            'type' => 'required|string|in:sale,adoption',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'fee' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'The selected market type is invalid. It must be either "sale" or "adoption".',
        ];
    }
}