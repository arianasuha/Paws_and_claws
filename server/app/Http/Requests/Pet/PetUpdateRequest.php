<?php

namespace App\Http\Requests\Pet;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PetUpdateRequest extends BaseRequest
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
            'name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
            'species' => 'sometimes|string|max:255',
            'breed' => 'nullable|string|max:255',
            'dob' => 'sometimes|date',
            'image_url' => 'nullable|image|max:2048',
            'user_id' => 'prohibited',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'You cannot modify the owner of a pet.',
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