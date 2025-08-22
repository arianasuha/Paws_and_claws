<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PetMarketRegisterRequest extends FormRequest
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
            // Rules for the nested 'pet' array
            'pet.name' => 'required|string|max:255',
            'pet.species' => 'required|string|max:255',
            'pet.breed' => 'required|string|max:255',
            'pet.gender' => 'required|in:male,female',
            'pet.dob' => 'required|date',
            'pet.height' => 'sometimes|numeric|min:1',
            'pet.weight' => 'sometimes|numeric|min:1',
            'pet.image_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Rule for image file

            // Rules for the nested 'market' array
            'market.type' => 'required|string|in:sale,adoption', // 'adopt' is corrected to 'adoption'
            'market.description' => 'nullable|string',
            'market.fee' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'pet.image_url.image' => 'The file must be an image.',
            'pet.image_url.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'market.type.in' => 'The selected market type is invalid. It must be either "sale" or "adoption".',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}